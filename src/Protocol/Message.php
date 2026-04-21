<?php

declare(strict_types=1);



// NOTE: In FinTsTestCase, this namespace name is hard-coded in order to be able to mock the rand() function below.

namespace BytesCommerce\Protocol;

use BytesCommerce\Model\NoPsd2TanMode;
use BytesCommerce\Model\TanMode;
use BytesCommerce\Options\Credentials;
use BytesCommerce\Options\FinTsOptions;
use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\HIRMS\Rueckmeldung;
use BytesCommerce\Segment\HIRMS\RueckmeldungContainer;
use BytesCommerce\Segment\HNHBK\HNHBKv3;
use BytesCommerce\Segment\HNHBS\HNHBSv1;
use BytesCommerce\Segment\HNSHA\BenutzerdefinierteSignaturV1;
use BytesCommerce\Segment\HNSHA\HNSHAv2;
use BytesCommerce\Segment\HNSHK\HNSHKv4;
use BytesCommerce\Segment\HNVSD\HNVSDv1;
use BytesCommerce\Segment\HNVSK\HNVSKv3;
use BytesCommerce\Syntax\Parser;
use BytesCommerce\Syntax\Serializer;

/**
 * NOTE: There is also the (newer) BytesCommerce\Protocol\Message class.
 *
 * This class builds a message that has the structure of an encrypted message as defined in the original HBCI
 * specification (first link below). However, it implements only the structure and no actual encryption or cryptographic
 * signature, because the PIN/TAN specification says not to use the HBCI cryptosystem -- instead there is just
 * encryption on the transport level (TLS), which this library implements through Curl provided that the user connects
 * to an HTTPS address.
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Security_Sicherheitsverfahren_HBCI_Rel_20181129_final_version.pdf
 * Section B.5
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Security_Sicherheitsverfahren_PINTAN_2018-02-23_final_version.pdf
 * Section A
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Formals_2017-10-06_final_version.pdf
 * Section B.8
 */
class Message
{
    /**
     * The segments in the original message structure, i.e. before wrapping/"encryption" or after
     * unwrapping/"decryption". This excludes all the headers/footers.
     * @var BaseSegment[]
     */
    public $plainSegments = [];

    /**
     * The wrapper segments that form the "encrypted" message structure, which includes the plain segments in HNVSD.
     *  - No. 1: HNHBK Message header
     *  - No. 998: HNVSK Encryption header
     *  - No. 999: HNVSD "Encrypted" contents, actually just wrapped plaintext.
     *    * No. 2 HNSHK Signature head (starts at 2 because there would implicitly be a HNHBK at 1)
     *    * No. 3 through N+2: All the $plainSegments (with N := count($plainSegments))
     *    * No. N+3: HNSHA Signature footer
     *  - No. N+4: HNHBS Message footer
     * @var BaseSegment[]
     */
    public $wrapperSegments = [];

    /**
     * The same HNHBK segment that is also stored inside $wrappedSegments above.
     * @var HNHBKv3
     */
    public $header;

    /**
     * The same HNHBS segment that is also stored inside $wrappedSegments above.
     * @var HNHBSv1
     */
    public $footer;

    /** @var HNSHKv4|null */
    public $signatureHeader;

    /** @var HNSHAv2|null */
    public $signatureFooter;

    /**
     * @return \Generator|BaseSegment[] All plain and wrapper segments in this message.
     */
    public function getAllSegments(): \Generator
    {
        yield from $this->plainSegments;
        yield from $this->wrapperSegments;
    }

    /**
     * @throws \InvalidArgumentException If any segment in this message is invalid.
     */
    public function validate(): void
    {
        foreach ($this->getAllSegments() as $allSegment) {
            try {
                $allSegment->validate();
            } catch (\InvalidArgumentException $e) {
                throw new \InvalidArgumentException('Invalid segment ' . $allSegment->segmentkopf->segmentkennung, 0, $e);
            }
        }
    }

    // TODO Add unit test coverage for the functions below.

    /**
     * @param string $segmentType The PHP type (class name or interface) of the segment(s).
     * @return BaseSegment[] All segments of this type (possibly an empty array).
     */
    public function findSegments(string $segmentType): array
    {
        return array_values(array_filter($this->plainSegments, 
            /* @var BaseSegment $segment */
            fn(\BytesCommerce\Segment\BaseSegment $segment) => $segment instanceof $segmentType));
    }

    /**
     * @param string $segmentType The PHP type (class name or interface) of the segment.
     * @return BaseSegment|null The segment, or null if it was found.
     */
    public function findSegment(string $segmentType): ?BaseSegment
    {
        $matchedSegments = $this->findSegments($segmentType);
        if (count($matchedSegments) > 1) {
            throw new UnexpectedResponseException('Multiple segments matched ' . $segmentType);
        }

        return $matchedSegments === [] ? null : $matchedSegments[0];
    }

    /**
     * @param string $segmentType The PHP type (class name or interface) of the segment.
     * @return bool Whether any such segment exists.
     */
    public function hasSegment(string $segmentType): bool
    {
        return $this->findSegment($segmentType) instanceof \BytesCommerce\Segment\BaseSegment;
    }

    /**
     * @param string $segmentType The PHP type (class name or interface) of the segment.
     * @return BaseSegment The segment, never null.
     * @throws UnexpectedResponseException If the segment was not found.
     */
    public function requireSegment(string $segmentType): BaseSegment
    {
        $matchedSegment = $this->findSegment($segmentType);
        if (!$matchedSegment instanceof \BytesCommerce\Segment\BaseSegment) {
            throw new UnexpectedResponseException('Segment not found: ' . $segmentType);
        }

        return $matchedSegment;
    }

    /**
     * @param int $segmentNumber The segment number to search for.
     * @return BaseSegment|null The segment with that number, or null if there is none.
     */
    public function findSegmentByNumber(int $segmentNumber): ?BaseSegment
    {
        foreach ($this->getAllSegments() as $allSegment) {
            if ($allSegment->getSegmentNumber() === $segmentNumber) {
                return $allSegment;
            }
        }

        return null;
    }

    /**
     * @param int[] $referenceNumbers The numbers of the reference segments.
     * @return Message A new message that just contains the plain segment from $this message which refer to one
     *     of the given $referenceSegments.
     */
    public function filterByReferenceSegments(array $referenceNumbers): Message
    {
        $message = new Message();
        if ($referenceNumbers === []) {
            return $message;
        }

        $message->plainSegments = array_filter($this->plainSegments, function (\BytesCommerce\Segment\BaseSegment $baseSegment) use ($referenceNumbers): bool {
            /** @var BaseSegment $segment */
            $referenceNumber = $baseSegment->segmentkopf->bezugselement;
            return $referenceNumber !== null && in_array($referenceNumber, $referenceNumbers);
        });
        $message->header = $this->header;
        $message->footer = $this->footer;
        $message->signatureHeader = $this->signatureHeader;
        $message->signatureFooter = $this->signatureFooter;
        return $message;
    }

    /**
     * @param int $code The response code to search for.
     * @param ?int $requestSegmentNumber If set, only consider Rueckmeldungen that pertain to this request segment.
     * @return Rueckmeldung|null The corresponding Rueckmeldung instance, or null if not found.
     */
    public function findRueckmeldung(int $code, ?int $requestSegmentNumber = null): ?Rueckmeldung
    {
        foreach ($this->plainSegments as $plainSegment) {
            if (
                $plainSegment instanceof RueckmeldungContainer && (
                    $requestSegmentNumber === null || $plainSegment->segmentkopf->bezugselement === $requestSegmentNumber
                )
            ) {
                $rueckmeldung = $plainSegment->findRueckmeldung($code);
                if ($rueckmeldung instanceof \BytesCommerce\Segment\HIRMS\Rueckmeldung) {
                    return $rueckmeldung;
                }
            }
        }

        return null;
    }

    /** @return Rueckmeldung[] */
    public function findRueckmeldungen(int $code): array
    {
        $rueckmeldungen = [];
        foreach ($this->plainSegments as $plainSegment) {
            if ($plainSegment instanceof RueckmeldungContainer) {
                $rueckmeldungen = array_merge($rueckmeldungen, $plainSegment->findRueckmeldungen($code));
            }
        }

        return $rueckmeldungen;
    }

    /**
     * @param int $requestSegmentNumber Only consider Rueckmeldungen that pertain to this request segment.
     * @return int[] The codes of all the Rueckmeldung instances matching the request segment.
     */
    public function findRueckmeldungscodesForReferenceSegment(int $requestSegmentNumber): array
    {
        $codes = [];
        foreach ($this->plainSegments as $plainSegment) {
            if ($plainSegment instanceof RueckmeldungContainer && $plainSegment->segmentkopf->bezugselement === $requestSegmentNumber) {
                foreach ($plainSegment->getAllRueckmeldungen() as $rueckmeldung) {
                    $codes[] = $rueckmeldung->rueckmeldungscode;
                }
            }
        }

        return $codes;
    }

    /**
     * @return string The HBCI/FinTS wire format for this message, ISO-8859-1 encoded.
     */
    public function serialize(): string
    {
        return Serializer::serializeSegments($this->wrapperSegments);
    }

    /**
     * Wraps the given segments in an "encryption" envelope (see class documentation). Inverse of {@link parse()}.
     * @param BaseSegment[]|MessageBuilder $plainSegments The plain segments to be wrapped. Segment numbers do not need
     *     to be set yet (or they will be overwritten).
     * @param FinTsOptions $finTsOptions See {@link FinTsOptions}.
     * @param string $kundensystemId See {@link $kundensystemId}.
     * @param Credentials $credentials The credentials used to authenticate the message.
     * @param TanMode|null $tanMode Optionally specifies which two-step TAN mode to use, defaults to 999 (single step).
     * @param string|null The TAN to be sent to the server (in HNSHA). If this is present, $tanMode must be present.
     * @return Message The built message, ready to be sent to the server through {@link FinTs::sendMessage()}.
     */
    public static function createWrappedMessage($plainSegments, FinTsOptions $finTsOptions, string $kundensystemId, Credentials $credentials, ?TanMode $tanMode, ?string $tan): Message
    {
        $message = new Message();
        $message->plainSegments = $plainSegments instanceof MessageBuilder ? $plainSegments->segments : $plainSegments;

        $tanMode = $tanMode instanceof NoPsd2TanMode ? null : $tanMode;
        $randomReference = strval(random_int(1000000, 9999999)); // Call unqualified rand() for unit test mocking to work.
        $benutzerdefinierteSignaturV1 = BenutzerdefinierteSignaturV1::create($credentials->getPin(), $tan);
        $numPlainSegments = count($message->plainSegments); // This is N, see $encryptedSegments.

        $message->wrapperSegments = [ // See $encryptedSegments documentation for the structure.
            $message->header = HNHBKv3::createEmpty()->setSegmentNumber(1),
            HNVSKv3::create($finTsOptions, $credentials, $kundensystemId, $tanMode), // Segment number 998
            HNVSDv1::create(array_merge( // Segment number 999
                [$message->signatureHeader = HNSHKv4::create(
                    $randomReference, $finTsOptions, $credentials, $tanMode, $kundensystemId
                )->setSegmentNumber(2)],
                static::setSegmentNumbers($message->plainSegments, 3),
                [$message->signatureFooter = HNSHAv2::create($randomReference, $benutzerdefinierteSignaturV1)
                    ->setSegmentNumber($numPlainSegments + 3), ]
            )),
            $message->footer = HNHBSv1::createEmpty()->setSegmentNumber($numPlainSegments + 4),
        ];

        return $message;
    }

    /**
     * Builds a plain message by adding header and footer to the given segments, but no "encryption" envelope.
     * Inverse of {@link parse()}.
     * @param BaseSegment[]|MessageBuilder $segments
     * @return Message The built message, ready to be sent to the server through {@link FinTs::sendMessage()}.
     */
    public static function createPlainMessage($segments): Message
    {
        $message = new Message();
        $message->plainSegments = $segments instanceof MessageBuilder ? $segments->segments : $segments;
        $message->wrapperSegments = array_merge(
            [$message->header = HNHBKv3::createEmpty()->setSegmentNumber(1)],
            static::setSegmentNumbers($message->plainSegments, 2),
            [$message->footer = HNHBSv1::createEmpty()->setSegmentNumber(2 + count($message->plainSegments))]
        );
        return $message;
    }

    /**
     * Parses the given wire format and unwraps the "encryption" envelope (see class documentation) if it exists
     * (in which case this function acts as the inverse of {@link createWrappedMessage()}), or leaves as is otherwise
     * (and acts as inverse of {@link createPlainMessage()}).
     *
     * @param string $rawMessage The received message in HBCI/FinTS wire format. This should be ISO-8859-1-encoded.
     * @return Message The parsed message.
     * @throws \InvalidArgumentException When the parsing fails.
     */
    public static function parse(string $rawMessage): Message
    {
        $message = new Message();
        $segments = Parser::parseSegments($rawMessage);

        // Message header and footer must always be there, or something went badly wrong.
        $message->header = $segments[0];
        $message->footer = $segments[count($segments) - 1];
        if (!$message->header instanceof HNHBKv3) {
            $actual = $message->header->getName();
            throw new \InvalidArgumentException(sprintf('Expected first segment to be HNHBK, but got %s: %s', $actual, $rawMessage));
        }

        if (!$message->footer instanceof HNHBSv1) {
            $actual = $message->footer->getName();
            throw new \InvalidArgumentException(sprintf('Expected last segment to be HNHBS, but got %s: %s', $actual, $rawMessage));
        }

        // Check if there's an encryption header and "encrypted" data.
        // Section B.8 specifies that there are exactly 4 segments: HNHBK, HNVSK, HNVSD, HNHBS.
        if (count($segments) === 4 && $segments[1] instanceof HNVSKv3) {
            if (!$segments[2] instanceof HNVSDv1) {
                throw new \InvalidArgumentException('Expected third segment to be HNVSD: ' . $rawMessage);
            }

            $message->wrapperSegments = $segments;
            $message->plainSegments = Parser::parseSegments($segments[2]->datenVerschluesselt->getData());

            // Signature header and footer must always be there when the "encrypted" structure was used.
            // Postbank is not following the Spec and does not send the Header and Footer

            $signatureFooterAsExpected = end($message->plainSegments) instanceof HNSHAv2;
            $signatureHeaderAsExpected = reset($message->plainSegments) instanceof HNSHKv4;

            if ($signatureHeaderAsExpected xor $signatureFooterAsExpected) {
                throw new \InvalidArgumentException('Expected first segment to be HNSHK and last segement to be HNSHA or both to be absent: ' . $rawMessage);
            }

            if ($signatureHeaderAsExpected) {
                $message->signatureHeader = array_shift($message->plainSegments);
            }

            if ($signatureFooterAsExpected) {
                $message->signatureFooter = array_pop($message->plainSegments);
            }
        } else {
            // Ensure that there's no encryption header anywhere, and we haven't just misunderstood the format.
            foreach ($segments as $segment) {
                if ($segment->getName() === 'HNVSK' || $segment->getName() === 'HNVSD') {
                    throw new \InvalidArgumentException('Unexpected encrypted format: ' . $rawMessage);
                }
            }

            $message->plainSegments = $segments; // The message wasn't "encrypted".
        }

        return $message;
    }

    /**
     * @param BaseSegment[] $segments The segments to be numbered. Will be modified.
     * @param int $segmentNumber The number for the *first* segment, subsequent segment get the subsequent integers.
     * @return BaseSegment[] The same array, for chaining.
     */
    public static function setSegmentNumbers(array $segments, int $segmentNumber): array
    {
        foreach ($segments as $segment) {
            $segment->segmentkopf->segmentnummer = $segmentNumber;
            if ($segment->segmentkopf->segmentnummer >= HNVSKv3::SEGMENT_NUMBER) {
                throw new \InvalidArgumentException('Too many segments');
            }

            ++$segmentNumber;
        }

        return $segments;
    }
}

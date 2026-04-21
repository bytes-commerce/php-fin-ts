<?php

declare(strict_types=1);



namespace BytesCommerce\Action;

use BytesCommerce\Model\SEPAAccount;
use BytesCommerce\Model\StatementOfHoldings\StatementOfHoldings;
use BytesCommerce\MT535\MT535;
use BytesCommerce\PaginateableAction;
use BytesCommerce\Protocol\BPD;
use BytesCommerce\Protocol\Message;
use BytesCommerce\Protocol\UnexpectedResponseException;
use BytesCommerce\Protocol\UPD;
use BytesCommerce\Segment\Common\KtvV3;
use BytesCommerce\Segment\HIRMS\Rueckmeldungscode;
use BytesCommerce\Segment\WPD\HIWPD;
use BytesCommerce\Segment\WPD\HIWPDS;
use BytesCommerce\Segment\WPD\HIWPDv5;
use BytesCommerce\Segment\WPD\HKWPDv5;
use BytesCommerce\UnsupportedException;

/**
 * Depotaufstellung HKWPD
 * MT535
 */
class GetDepotAufstellung extends PaginateableAction
{
    // Request (if you add a field here, update __serialize() and __unserialize() as well).
    private ?\BytesCommerce\Model\SEPAAccount $sepaAccount = null;

    // Response
    private string $rawMT535 = '';

    private ?\BytesCommerce\Model\StatementOfHoldings\StatementOfHoldings $statementOfHoldings = null;

    private ?float $depotWert = null;

    /**
     * @param SEPAAccount $sepaAccount The account to get the statement for. This can be constructed based on information
     *     that the user entered, or it can be {@link SEPAAccount} instance retrieved from {@link getAccounts()}.
     * @return GetDepotAufstellung A new action instance.
     */
    public static function create(SEPAAccount $sepaAccount): GetDepotAufstellung
    {
        $getDepotAufstellung = new GetDepotAufstellung();
        $getDepotAufstellung->sepaAccount = $sepaAccount;
        return $getDepotAufstellung;
    }

    /**
     * @deprecated Beginning from PHP7.4 __unserialize is used for new generated strings, then this method is only used for previously generated strings - remove after May 2023
     */
    public function serialize(): string
    {
        return serialize($this->__serialize());
    }

    public function __serialize(): array
    {
        return [
            parent::__serialize(),
            $this->sepaAccount,
        ];
    }

    /**
     * @deprecated Beginning from PHP7.4 __unserialize is used for new generated strings, then this method is only used for previously generated strings - remove after May 2023
     *
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        self::__unserialize(unserialize($serialized));
    }

    public function __unserialize(array $serialized): void
    {
        list(
            $parentSerialized,
            $this->sepaAccount,
        ) = $serialized;

        is_array($parentSerialized) ?
            parent::__unserialize($parentSerialized) :
            parent::unserialize($parentSerialized);
    }

    /**
     * @return string The raw MT535 data received from the server.
     * @noinspection PhpUnused
     */
    public function getRawMT535(): string
    {
        $this->ensureDone();
        return $this->rawMT535;
    }

    public function getStatement(): StatementOfHoldings
    {
        $this->ensureDone();
        return $this->statementOfHoldings;
    }

    public function getDepotWert(): float
    {
        $this->ensureDone();
        return $this->depotWert;
    }

    protected function createRequest(BPD $bpd, ?UPD $upd)
    {
        /** @var HIWPDS $baseSegment */
        $baseSegment = $bpd->requireLatestSupportedParameters('HIWPDS');

        return match ($baseSegment->getVersion()) {
            5 => HKWPDv5::create(KtvV3::fromAccount($this->sepaAccount)),
            default => throw new UnsupportedException('Unsupported HKWPD version: ' . $baseSegment->getVersion()),
        };
    }

    public function processResponse(Message $message): void
    {
        parent::processResponse($message);

        $isUnavailable = $message->findRueckmeldung(Rueckmeldungscode::NICHT_VERFUEGBAR) instanceof \BytesCommerce\Segment\HIRMS\Rueckmeldung;
        $responseHiwpd = $message->findSegments(HIWPDv5::class);

        $numResponseSegments = count($responseHiwpd);
        if (!$isUnavailable && $numResponseSegments < count($this->getRequestSegmentNumbers())) {
            throw new UnexpectedResponseException(sprintf('Only got %d HIWPD response segments!', $numResponseSegments));
        }

        /** @var HIWPD $hiwpd */
        foreach ($responseHiwpd as $hiwpd) {
            $this->rawMT535 .= $hiwpd->getDepotaufstellung()->getData();
        }

        // Note: Pagination boundaries may cut in the middle of the MT535 data, so it is not possible to parse a partial
        // reponse before having received all pages.
        if (!$this->hasMorePages()) {
            $this->parseMt535();
        }
    }

    private function parseMt535(): void
    {
        try {
            // Note: Some banks encode their MT 535 data as SWIFT/ISO-8859 like it should be according to the
            // specification, others just send UTF-8, so we try to detect it here.
            $rawMT535 = mb_detect_encoding($this->rawMT535, 'UTF-8', true) === false
                ? mb_convert_encoding($this->rawMT535, 'UTF-8', 'ISO-8859-1') : $this->rawMT535;
            $mt535 = new MT535($rawMT535);
            $this->statementOfHoldings = $mt535->parseHoldings();
            $this->depotWert = $mt535->parseDepotWert();
        } catch (\Exception $exception) {
            throw new \InvalidArgumentException('Invalid MT535 data', 0, $exception);
        }
    }
}

<?php

declare(strict_types=1);


/** @noinspection PhpUnused */

namespace BytesCommerce\Protocol;

use BytesCommerce\Model\TanMode;
use BytesCommerce\Segment\AnonymousSegment;
use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\HIBPA\HIBPAv3;
use BytesCommerce\Segment\HIPINS\HIPINSv1;
use BytesCommerce\Segment\SegmentInterface;
use BytesCommerce\Segment\TAN\HITANS;
use BytesCommerce\Segment\VPP\HIVPPSv1;

/**
 * Segmentfolge: Bankparameterdaten (Version 3)
 *
 * Contains the "Bankparameterdaten" (BPD), i.e. configuration information that was retrieved from the bank server
 * during a synchronization. This library currently does not store persisted BPD, so it just retrieves them freshly
 * every time.
 *
 * Note: The following segments are part of BPD but not explicity implemented in this library:
 * - HIKOM (lists physical communication channels to the bank, but this library only supports HTTPS and the library user
 *   needs to specify the URL explicitly, so there is no need to know the HIKOM contents).
 * - HISHV (lists security protocols that the bank supports, but this library only supports PIN/TAN).
 * - HIKPV (lists compression protocols, but this library supports none).
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Formals_2017-10-06_final_version.pdf
 * Section D.1
 */
class BPD
{
    /** @var HIBPAv3 The HIBPA segment received from the server, which contains most of the BPD data. */
    public $hibpa;

    /**
     * The parameters for each business transaction type, indexed in a nested array structure:
     * - Outer keys are segment identifiers (e.g. 'HIKAZS')
     * - Inner keys are segment versions (these keys are numerically sorted in DESCENDING order, so that the newest and
     *   thus most interesting segment is first)
     * - Inner values are the (possibly anonymous) parameter segments.
     * @var BaseSegment[][]
     */
    public $parameters = [];

    /** @var bool Whether the fake TAN mode 999 is allowed. */
    public $singleStepTanModeAllowed;

    /**
     * @var bool[] An array mapping business transaction request types ('HKxyz' strings) to a bool indicating whether
     *     the respective business transaction needs a TAN, according to the HIPINS information.
     */
    public $tanRequired = [];

    /**
     * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Security_Sicherheitsverfahren_PINTAN_2018-02-23_final_version.pdf
     * Section: B.8.2
     * @var TanMode[] All TAN modes supported by this bank, indexed by their IDs. Note that the UPD contains the modes
     * that the user can use.
     */
    public $allTanModes = [];

    public function getVersion()
    {
        return $this->hibpa->bpdVersion;
    }

    public function getBankCode()
    {
        return $this->hibpa->kreditinstitutskennung->kreditinstitutscode;
    }

    public function getBankName()
    {
        return $this->hibpa->kreditinstitutsbezeichnung;
    }

    /**
     * @param string $type A business transaction type, represented by the segment name of the respective parameter
     *     segment (Geschäftsvorfallparameter segment, aka. Segmentparametersegment). Example: 'HIKAZS'.
     * @return BaseSegment[] All parameter segments of that type ordered descendingly by version (newest first),
     *     excluding such that are not explicitly implemented in this library (no AnonymousSegments). The returned array
     *     is possibly empty if no versions offered by the bank are also supported by the library.
     */
    public function getAllSupportedParameters(string $type): array
    {
        return array_filter($this->parameters[$type] ?? [], fn(BaseSegment $segment) => !$segment instanceof AnonymousSegment);
    }

    /**
     * @param string $type A business transaction type, represented by the segment name of the respective parameter
     *     segment (Geschäftsvorfallparameter segment, aka. Segmentparametersegment). Example: 'HIKAZS'.
     * @return BaseSegment|null The latest parameter segment that is explicitly implemented in this library (never an
     *     AnonymousSegment), or null if none exists.
     */
    public function getLatestSupportedParameters(string $type): ?BaseSegment
    {
        if (!array_key_exists($type, $this->parameters)) {
            return null;
        }

        foreach ($this->parameters[$type] as $segment) {
            if (!$segment instanceof AnonymousSegment) {
                return $segment;
            }
        }

        return null;
    }

    /**
     * @param string $type A business transaction type, see above.
     * @return BaseSegment The latest parameter segment, never null.
     * @throws UnexpectedResponseException If no version exists.
     */
    public function requireLatestSupportedParameters(string $type): BaseSegment
    {
        $result = $this->getLatestSupportedParameters($type);
        if (!$result instanceof \BytesCommerce\Segment\BaseSegment) {
            throw new UnexpectedResponseException(
                sprintf('The server does not support any %s versions implemented in this library', $type));
        }

        return $result;
    }

    /**
     * @param string $type A business transaction type, see above.
     * @param int $version The segment version of the business transaction.
     * @return bool If that version of the given transaction type is supported by the bank.
     */
    public function supportsParameters(string $type, int $version): bool
    {
        foreach ($this->parameters[$type] as $segment) {
            if ($segment->getVersion() === $version) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param SegmentInterface[] $requestSegments The segments that shall be sent to the bank.
     * @return string|null Identifier of the (first) segment that requires a TAN according to HIPINS, or null if none of
     *     the segments require a TAN.
     */
    public function tanRequiredForRequest(array $requestSegments): ?string
    {
        foreach ($requestSegments as $requestSegment) {
            if ($this->tanRequired[$requestSegment->getName()] ?? false) {
                return $requestSegment->getName();
            }
        }

        return null;
    }

    /**
     * @param SegmentInterface[] $requestSegments The segments that shall be sent to the bank.
     * @return string|null Identifier of the (first) segment that requires Verification of Payee according to HIPINS, or
     *     null if none of the segments require verification.
     */
    public function vopRequiredForRequest(array $requestSegments): ?string
    {
        /** @var HIVPPSv1 $hivpps */
        $hivpps = $this->getLatestSupportedParameters('HIVPPS');
        $vopRequiredTypes = $hivpps?->parameter?->vopPflichtigerZahlungsverkehrsauftrag;
        if ($vopRequiredTypes === null) {
            return null;
        }

        foreach ($requestSegments as $requestSegment) {
            if (in_array($requestSegment->getName(), $vopRequiredTypes)) {
                return $requestSegment->getName();
            }
        }

        return null;
    }

    /**
     * @return bool Whether the BPD indicates that the bank supports PSD2.
     */
    public function supportsPsd2(): bool
    {
        return $this->supportsParameters('HITANS', 6);
    }

    /**
     * @param Message $message The dialog initialization response from the server.
     * @return BPD A new BPD instance with the extracted configuration data.
     */
    public static function extractFromResponse(Message $message): BPD
    {
        $bpd = new BPD();
        $bpd->hibpa = $message->requireSegment(HIBPAv3::class);

        // Extract the HIxyzS segments, which contain parameters that describe how (future) requests for the particular
        // type of business transaction have to look.
        foreach ($message->plainSegments as $segment) {
            $segmentName = $segment->getName();
            if (strlen($segmentName) === 6 && $segmentName[5] === 'S') {
                $bpd->parameters[$segmentName][$segment->getVersion()] = $segment;
                krsort($bpd->parameters[$segmentName]); // Newest first.
            }
        }

        ksort($bpd->parameters); // Sort alphabetically, for easier debugging.
        // Extract from HIPINS which HKxyz requests will need a TAN.
        /** @var HIPINSv1 $baseSegment */
        $baseSegment = $message->requireSegment(HIPINSv1::class);
        foreach ($baseSegment->parameter->geschaeftsvorfallspezifischePinTanInformationen as $typeInfo) {
            $bpd->tanRequired[$typeInfo->segmentkennung] = $typeInfo->tanErforderlich;
        }

        // Extract all TanModes from HIPINS.
        if ($bpd->supportsPsd2()) {
            /** @var HITANS[] $allHitans */
            $allHitans = $bpd->getAllSupportedParameters('HITANS');
            if (count($allHitans) === 0) {
                throw new UnexpectedResponseException(
                    'The server does not support any HITANS versions implemented in this library');
            }

            foreach ($allHitans as $allHitan) {
                $tanParams = $allHitan->getParameterZweiSchrittTanEinreichung();
                $bpd->singleStepTanModeAllowed = $tanParams->isEinschrittVerfahrenErlaubt();
                foreach ($tanParams->getVerfahrensparameterZweiSchrittVerfahren() as $verfahren) {
                    if (!array_key_exists($verfahren->getId(), $bpd->allTanModes)) {
                        $bpd->allTanModes[$verfahren->getId()] = $verfahren;
                    }
                }
            }
        }

        return $bpd;
    }
}

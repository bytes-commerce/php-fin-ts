<?php

declare(strict_types=1);



namespace BytesCommerce\Action;

use BytesCommerce\BaseAction;
use BytesCommerce\Protocol\BPD;
use BytesCommerce\Protocol\UPD;
use BytesCommerce\Segment\DSE\HIDXES;
use BytesCommerce\Segment\DSE\MinimaleVorlaufzeitSEPALastschrift;

/**
 * Retrieves information about SEPA Direct Debit Requests
 */
class GetSEPADirectDebitParameters extends BaseAction
{
    public const SEQUENCE_TYPES = ['FRST', 'OOFF', 'FNAL', 'RCUR'];

    public const DIRECT_DEBIT_TYPES = ['CORE', 'COR1', 'B2B'];

    // Request (if you add a field here, update __serialize() and __unserialize() as well).
    private ?string $directDebitType = null;

    private ?string $seqType = null;

    private ?bool $singleDirectDebit = null;

    private ?\BytesCommerce\Segment\BaseSegment $baseSegment = null;

    public static function create(string $seqType, bool $singleDirectDebit, string $directDebitType = 'CORE'): \BytesCommerce\Action\GetSEPADirectDebitParameters
    {
        if (!in_array($directDebitType, self::DIRECT_DEBIT_TYPES, true)) {
            throw new \InvalidArgumentException('Unknown CORE type, possible values are ' . implode(', ', self::DIRECT_DEBIT_TYPES));
        }

        if (!in_array($seqType, self::SEQUENCE_TYPES, true)) {
            throw new \InvalidArgumentException('Unknown SEPA sequence type, possible values are ' . implode(', ', self::SEQUENCE_TYPES));
        }

        $getSEPADirectDebitParameters = new GetSEPADirectDebitParameters();
        $getSEPADirectDebitParameters->directDebitType = $directDebitType;
        $getSEPADirectDebitParameters->seqType = $seqType;
        $getSEPADirectDebitParameters->singleDirectDebit = $singleDirectDebit;
        return $getSEPADirectDebitParameters;
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
            $this->directDebitType, $this->seqType, $this->singleDirectDebit,
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
            $this->directDebitType, $this->seqType, $this->singleDirectDebit,
        ) = $serialized;

        is_array($parentSerialized) ?
            parent::__unserialize($parentSerialized) :
            parent::unserialize($parentSerialized);
    }

    public static function getHixxesSegmentName(string $directDebitType, bool $singleDirectDebit): string
    {
        return match ($directDebitType) {
            'CORE', 'COR1' => $singleDirectDebit ? 'HIDSES' : 'HIDMES',
            'B2B' => $singleDirectDebit ? 'HIBSES' : 'HIBMES',
            default => throw new \InvalidArgumentException('Unknown DirectDebitTypes type, possible values are ' . implode(', ', self::DIRECT_DEBIT_TYPES)),
        };
    }

    protected function createRequest(BPD $bpd, ?UPD $upd): array
    {
        $this->baseSegment = $bpd->requireLatestSupportedParameters(static::getHixxesSegmentName($this->directDebitType, $this->singleDirectDebit));
        $this->isDone = true;
        return []; // No request to the bank required
    }

    /**
     * @return MinimaleVorlaufzeitSEPALastschrift|null The information about the lead time for the given Sequence Type and Direct Debit Type
     */
    public function getMinimalLeadTime(): ?MinimaleVorlaufzeitSEPALastschrift
    {
        $parsed = $this->baseSegment->getParameter()->getMinimalLeadTime($this->seqType);
        if ($parsed instanceof MinimaleVorlaufzeitSEPALastschrift) {
            return $parsed;
        }

        return $parsed[$this->directDebitType] ?? null;
    }
}

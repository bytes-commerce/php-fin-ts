<?php

namespace Fhp\Segment\CCM;

use Fhp\Segment\BaseDeg;

class ParameterSEPASammelueberweisungV1 extends BaseDeg
{
    public int $maximaleAnzahlCreditTransferTransactionInformation;
    public bool $summenfeldBenoetigt;
    public bool $einzelbuchungErlaubt;

    public function getMaximaleAnzahlCreditTransferTransactionInformation(): int
    {
        return $this->maximaleAnzahlCreditTransferTransactionInformation;
    }

    public function getSummenfeldBenoetigt(): bool
    {
        return $this->summenfeldBenoetigt;
    }

    public function getEinzelbuchungErlaubt(): bool
    {
        return $this->einzelbuchungErlaubt;
    }
}

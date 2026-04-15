<?php

namespace Fhp\Segment\CME;

use Fhp\Segment\BaseDeg;

class ParameterTerminierteSEPASammelueberweisungEinreichenV1 extends BaseDeg
{
    public int $minimaleVorlaufzeit;
    public int $maximaleVorlaufzeit;
    public int $maximaleAnzahlCreditTransferTransactionInformation;
    public bool $summenfeldBenoetigt;
    public bool $einzelbuchungErlaubt;

    public function getMinimaleVorlaufzeit(): int
    {
        return $this->minimaleVorlaufzeit;
    }

    public function getMaximaleVorlaufzeit(): int
    {
        return $this->maximaleVorlaufzeit;
    }

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

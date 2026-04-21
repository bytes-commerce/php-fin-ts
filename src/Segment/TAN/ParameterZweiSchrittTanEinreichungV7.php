<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\TAN;

use BytesCommerce\Segment\BaseDeg;

class ParameterZweiSchrittTanEinreichungV7 extends BaseDeg implements ParameterZweiSchrittTanEinreichung
{
    public bool $einschrittVerfahrenErlaubt;

    public bool $mehrAlsEinTanPflichtigerAuftragProNachrichtErlaubt;

    /**
     * 0: Auftrags-Hashwert nicht unterstützt
     * 1: RIPEMD-160
     * 2: SHA-1
     */
    public int $auftragsHashwertverfahren;

    /** @var VerfahrensparameterZweiSchrittVerfahrenV7[] @Max(98) */
    public array $verfahrensparameterZweiSchrittVerfahren;

    public function isEinschrittVerfahrenErlaubt(): bool
    {
        return $this->einschrittVerfahrenErlaubt;
    }

    public function getVerfahrensparameterZweiSchrittVerfahren(): array
    {
        return $this->verfahrensparameterZweiSchrittVerfahren;
    }
}

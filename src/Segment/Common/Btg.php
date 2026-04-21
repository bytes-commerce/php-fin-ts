<?php

declare(strict_types=1);



namespace BytesCommerce\Segment\Common;

use BytesCommerce\Segment\BaseDeg;

/**
 * Mehrfach verwendetes Element: Betrag (Version 1)
 */
class Btg extends BaseDeg
{
    public float $wert;

    public string $waehrung;

    public function getWert(): float
    {
        return $this->wert;
    }

    public function getWaehrung(): string
    {
        return $this->waehrung;
    }

    public static function create(float $wert, string $waehrung = 'EUR'): Btg
    {
        $btg = new Btg();
        $btg->wert = $wert;
        $btg->waehrung = $waehrung;
        return $btg;
    }
}

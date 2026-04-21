<?php

declare(strict_types=1);



namespace BytesCommerce\Segment\Common;

use BytesCommerce\Segment\BaseDeg;

/**
 * Mehrfach verwendetes Element: Zeitstempel (Version 1)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: B.6
 */
class Tsp extends BaseDeg
{
    /** JJJJMMTT gemäß ISO 8601 */
    public string $datum;

    /** hhmmss gemäß ISO 8601, local time (no time zone support). */
    public ?string $uhrzeit = null;

    public static function create(string $datum, ?string $uhrzeit): Tsp
    {
        $tsp = new Tsp();
        $tsp->datum = $datum;
        $tsp->uhrzeit = $uhrzeit;
        return $tsp;
    }

    public function getDatum(): string
    {
        return $this->datum;
    }

    public function getUhrzeit(): ?string
    {
        return $this->uhrzeit;
    }

    public function asDateTime(): \DateTime
    {
        return \DateTime::createFromFormat('Ymd His', $this->datum . ' ' . ($this->uhrzeit ?? '000000'));
    }
}

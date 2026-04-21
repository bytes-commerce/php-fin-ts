<?php

declare(strict_types=1);



namespace BytesCommerce\Segment\Common;

use BytesCommerce\Segment\BaseDeg;

/**
 * Mehrfach verwendetes Element: Kreditinstitutskennung (Version 1)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: B.2
 */
class Kik extends BaseDeg
{
    public const DEFAULT_COUNTRY_CODE = '280'; // Germany

    /** (ISO 3166-1; has leading zeros; Germany is 280, see also chapter E.4 */
    public ?string $laenderkennzeichen = null;  // Officially it's mandatory, but in practice it can be missing.
    /** Max length: 30 (Mandatory/absent depending on the country) */
    public ?string $kreditinstitutscode = null;

    public function validate(): void
    {
        parent::validate();
        if ($this->laenderkennzeichen === self::DEFAULT_COUNTRY_CODE && $this->kreditinstitutscode === null) {
            throw new \InvalidArgumentException('Kik.kreditinstitutscode is mandatory for German banks (BLZ)');
        }
    }

    public static function create(string $kreditinstitutscode): Kik
    {
        $kik = new Kik();
        $kik->laenderkennzeichen = static::DEFAULT_COUNTRY_CODE;
        $kik->kreditinstitutscode = $kreditinstitutscode;
        return $kik;
    }

    public function getLaenderkennzeichen(): ?string
    {
        return $this->laenderkennzeichen;
    }

    public function getKreditinstitutscode(): ?string
    {
        return $this->kreditinstitutscode;
    }
}

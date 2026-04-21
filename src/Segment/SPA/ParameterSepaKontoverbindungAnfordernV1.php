<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\SPA;

use BytesCommerce\Segment\BaseDeg;
use BytesCommerce\Segment\UnterstuetzteSEPADatenformateTrait;

/**
 * Data Element Group: Parameter SEPA-Kontoverbindung anfordern (Version 1)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: D (letter P)
 */
class ParameterSepaKontoverbindungAnfordernV1 extends BaseDeg implements ParameterSepaKontoverbindungAnfordern
{
    use UnterstuetzteSEPADatenformateTrait;

    public bool $einzelkontenabrufErlaubt;

    public bool $nationaleKontoverbindungErlaubt;

    public bool $strukturierterVerwendungszweckErlaubt;

    /** @var string[] @Max(99) Max length each: 256 */
    public array $unterstuetzteSepaDatenformate;
}

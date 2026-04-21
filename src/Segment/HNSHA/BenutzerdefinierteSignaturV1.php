<?php

declare(strict_types=1);


/** @noinspection PhpUnused */

namespace BytesCommerce\Segment\HNSHA;

use BytesCommerce\Segment\BaseDeg;

/**
 * Data Element Group: Benutzerdefinierte Signatur (Version 1)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Security_Sicherheitsverfahren_PINTAN_2018-02-23_final_version.pdf
 * Section: D (letter B)
 */
class BenutzerdefinierteSignaturV1 extends BaseDeg
{
    /** Max length: 99 */
    public string $pin;

    /** Max length: 99 */
    public ?string $tan = null;

    public static function create(string $pin, ?string $tan): BenutzerdefinierteSignaturV1
    {
        $benutzerdefinierteSignaturV1 = new BenutzerdefinierteSignaturV1();
        $benutzerdefinierteSignaturV1->pin = $pin;
        $benutzerdefinierteSignaturV1->tan = $tan;
        return $benutzerdefinierteSignaturV1;
    }
}

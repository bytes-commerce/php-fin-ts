<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\HNSHA;

use BytesCommerce\Segment\BaseSegment;

/**
 * Segment: Signaturabschluss (Version 2)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Security_Sicherheitsverfahren_HBCI_Rel_20181129_final_version.pdf
 * Section: B.5.2
 */
class HNSHAv2 extends BaseSegment
{
    /** Max length: 14; A nonce, that matches the one in HNSHK */
    public string $sicherheitskontrollreferenz;

    /** Max length: 512; not allowed for PIN/TAN */
    public ?string $validierungsresultat = null;

    public ?BenutzerdefinierteSignaturV1 $benutzerdefinierteSignatur = null;

    /**
     * @param string $sicherheitskontrollreferenz The same number that was passed to HNSHK.
     * @param BenutzerdefinierteSignaturV1 $benutzerdefinierteSignaturV1 Contains PIN, and optionally the TAN
     */
    public static function create(string $sicherheitskontrollreferenz, BenutzerdefinierteSignaturV1 $benutzerdefinierteSignaturV1): HNSHAv2
    {
        $hnshAv2 = HNSHAv2::createEmpty();
        $hnshAv2->sicherheitskontrollreferenz = $sicherheitskontrollreferenz;
        $hnshAv2->benutzerdefinierteSignatur = $benutzerdefinierteSignaturV1;
        return $hnshAv2;
    }
}

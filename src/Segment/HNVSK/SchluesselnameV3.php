<?php

declare(strict_types=1);


/** @noinspection PhpUnused */

namespace BytesCommerce\Segment\HNVSK;

use BytesCommerce\Segment\BaseDeg;

/**
 * Data ELement Group: Schlüsselname (Version 3)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Security_Sicherheitsverfahren_HBCI_Rel_20181129_final_version.pdf
 * Section: D
 */
class SchluesselnameV3 extends BaseDeg
{
    /**
     * Indicates that the key is to be used for cryptographic signatures.
     */
    public const SIGNIERSCHLUESSEL = 'S';

    /**
     * Indicates that the key is to be used for cryptographic ciphers (that is, encryption).
     */
    public const CHIFFRIERSCHLUESSEL = 'V';

    public \BytesCommerce\Segment\Common\Kik $kreditinstitutskennung;

    /** This is the username used for login. */
    public string $benutzerkennung;

    /**
     * D: Schlüssel zur Erzeugung digitaler Signaturen (DS-Schlüssel)
     * S: Signierschlüssel
     * V: Chiffrierschlüssel
     * (Version 2)
     */
    public string $schluesselart;

    public int $schluesselnummer = 0;
     // Dummy value for PIN/TAN.
    public int $schluesselversion = 0; // Dummy value for PIN/TAN.

    public static function create(\BytesCommerce\Segment\Common\Kik $kik, string $benutzerkennung, string $schluesselart): SchluesselnameV3
    {
        $schluesselnameV3 = new SchluesselnameV3();
        $schluesselnameV3->kreditinstitutskennung = $kik;
        $schluesselnameV3->benutzerkennung = $benutzerkennung;
        $schluesselnameV3->schluesselart = $schluesselart;
        return $schluesselnameV3;
    }
}

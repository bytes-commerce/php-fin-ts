<?php

declare(strict_types=1);


/** @noinspection PhpUnused */

namespace BytesCommerce\Segment\HNSHK;

use BytesCommerce\Model\TanMode;
use BytesCommerce\Options\Credentials;
use BytesCommerce\Options\FinTsOptions;
use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\Common\Kik;

/**
 * Segment: Signaturkopf (Version 4)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Security_Sicherheitsverfahren_HBCI_Rel_20181129_final_version.pdf
 * Section: B.5.1
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Security_Sicherheitsverfahren_PINTAN_2018-02-23_final_version.pdf
 * Section B.9.4
 */
class HNSHKv4 extends BaseSegment
{
    public \BytesCommerce\Segment\HNVSK\SicherheitsprofilV1 $sicherheitsprofil;

    /**
     * For the PIN/TAN profile (see section B.9.4), this must be:
     *   - 998 for Ein-Schritt-Verfahren, or
     *   - the value in the 900--997 range as received in
     *     {@link \BytesCommerce\Segment\TAN\VerfahrensparameterZweiSchrittVerfahrenv6::$sicherheitsfunktion}
     */
    public int $sicherheitsfunktion;

    /** Max length: 14; A nonce, that matches the one in HNSHA */
    public string $sicherheitskontrollreferenz;

    /**
     * 1: Signaturkopf und HBCI-Nutzdaten (SHM)
     * (not allowed: 2: Von Signaturkopf bis Signaturabschluss (SHT))
     * (Version 2)
     */
    public int $bereichDerSicherheitsapplikation = 1; // This is the only allowed value.
    /**
     * 1: Der Unterzeichner ist Herausgeber der signierten Nachricht, z. B. Erfasser oder Erstsignatur (ISS)
     * 3: Der Unterzeichner unterstützt den Inhalt der Nachricht, z. B. bei Zweitsignatur (CON)
     * 4: Der Unterzeichner ist Zeuge, aber für den Inhalt der Nachricht nicht verantwortlich, z. B. Übermittler,
     *    welcher nicht Erfasser ist (WIT)
     */
    public int $rolleDesSicherheitslieferanten = 1;

    public \BytesCommerce\Segment\HNVSK\SicherheitsidentifikationDetailsV2 $sicherheitsidentifikationDetails;

    public int $sicherheitsreferenznummer = 1;
     // Not used / supported by this library, so just a dummy value.
    public \BytesCommerce\Segment\HNVSK\SicherheitsdatumUndUhrzeitV2 $sicherheitsdatumUndUhrzeit;

    public HashalgorithmusV2 $hashalgorithmus;

    public SignaturalgorithmusV2 $signaturalgorithmus;

    public \BytesCommerce\Segment\HNVSK\SchluesselnameV3 $schluesselname;

    /** For the PIN/TAN profile, this must be empty (see section B.9.4). */
    public ?\BytesCommerce\Segment\HNVSK\ZertifikatV2 $zertifikat = null;

    /**
     * @param string $sicherheitskontrollreferenz A nonce (random number) to reference the corresponding HNSHA segment.
     * @param FinTsOptions $finTsOptions See {@link FinTsOptions}.
     * @param Credentials $credentials See {@link Credentials}.
     * @param TanMode|null $tanMode Optionally specifies which two-step TAN mode to use, defaults to 999 (single step).
     * @param string $kundensystemId See {@link SicherheitsidentifikationDetailsV2::$identifizierungDerPartei}.
     */
    public static function create(string $sicherheitskontrollreferenz, FinTsOptions $finTsOptions, Credentials $credentials, ?TanMode $tanMode, string $kundensystemId): HNSHKv4
    {
        $hnshKv4 = HNSHKv4::createEmpty();
        $hnshKv4->sicherheitsprofil =
            \BytesCommerce\Segment\HNVSK\SicherheitsprofilV1::createPIN($tanMode);
        $hnshKv4->sicherheitsfunktion = $tanMode instanceof \BytesCommerce\Model\TanMode ? $tanMode->getId() : TanMode::SINGLE_STEP_ID;
        $hnshKv4->sicherheitskontrollreferenz = $sicherheitskontrollreferenz;
        $hnshKv4->sicherheitsidentifikationDetails =
            \BytesCommerce\Segment\HNVSK\SicherheitsidentifikationDetailsV2::createForSender($kundensystemId);
        $hnshKv4->sicherheitsdatumUndUhrzeit =
            \BytesCommerce\Segment\HNVSK\SicherheitsdatumUndUhrzeitV2::now();
        $hnshKv4->hashalgorithmus = new HashalgorithmusV2();
        $hnshKv4->signaturalgorithmus = new SignaturalgorithmusV2();
        $hnshKv4->schluesselname = \BytesCommerce\Segment\HNVSK\SchluesselnameV3::create(
            Kik::create($finTsOptions->bankCode),
            $credentials->getBenutzerkennung(),
            \BytesCommerce\Segment\HNVSK\SchluesselnameV3::SIGNIERSCHLUESSEL);
        return $hnshKv4;
    }
}

<?php

declare(strict_types=1);


/** @noinspection PhpUnused */

namespace BytesCommerce\Segment\HKIDN;

use BytesCommerce\Options\Credentials;
use BytesCommerce\Segment\BaseSegment;

/**
 * Segment: Identifikation (Version 2)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Formals_2017-10-06_final_version.pdf
 * Section: C.3.1.2
 */
class HKIDNv2 extends BaseSegment
{
    /**
     * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Formals_2017-10-06_final_version.pdf
     * Section C.5
     */
    public const ANONYMOUS_KUNDEN_ID = '9999999999';

    public const MISSING_KUNDENSYSTEM_ID = '0';

    public \BytesCommerce\Segment\Common\Kik $kreditinstitutskennung;

    /** Max length: 30 */
    public string $kundenId;

    /** Max length: 30 */
    public string $kundensystemId;

    /**
     * 0: Kundensystem-ID wird nicht benötigt (HBCI DDV-Verfahren und chipkartenbasierte Verfahren ab
     *    Sicherheitsprofil-Version 3)
     * 1: Kundensystem-ID wird benötigt (sonstige HBCI RAH-/RDH- und PIN/TAN-Verfahren)
     */
    public int $kundensystemStatus = 1; // This library only supports PIN/TAN, hence 1 is the right choice.

    public static function create(string $kreditinstitutionscode, Credentials $credentials, string $kundensystemId): HKIDNv2
    {
        $hkidNv2 = HKIDNv2::createEmpty();
        $hkidNv2->kreditinstitutskennung = \BytesCommerce\Segment\Common\Kik::create($kreditinstitutionscode);
        $hkidNv2->kundenId = $credentials->getBenutzerkennung();
        $hkidNv2->kundensystemId = $kundensystemId;
        $hkidNv2->kundensystemStatus = 1; // This library only supports PIN/TAN, hence 1 is the right choice.
        return $hkidNv2;
    }

    public static function createAnonymous(string $kreditinstitutionscode): HKIDNv2
    {
        $hkidNv2 = HKIDNv2::createEmpty();
        $hkidNv2->kreditinstitutskennung = \BytesCommerce\Segment\Common\Kik::create($kreditinstitutionscode);
        $hkidNv2->kundenId = static::ANONYMOUS_KUNDEN_ID;
        $hkidNv2->kundensystemId = static::MISSING_KUNDENSYSTEM_ID;
        $hkidNv2->kundensystemStatus = 0; // Prescribed value for anonymous access.
        return $hkidNv2;
    }
}

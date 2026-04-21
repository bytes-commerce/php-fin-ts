<?php

declare(strict_types=1);


/** @noinspection PhpUnused */

namespace BytesCommerce\Segment\HKVVB;

use BytesCommerce\Options\FinTsOptions;
use BytesCommerce\Protocol\BPD;
use BytesCommerce\Protocol\UPD;
use BytesCommerce\Segment\BaseSegment;

/**
 * Segment: Verarbeitungsvorbereitung (Version 3)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Formals_2017-10-06_final_version.pdf
 * Section: C.3.1.3
 */
class HKVVBv3 extends BaseSegment
{
    public int $bpdVersion = 0;
     // 0 means no BPD stored at client-side yet.
    public int $updVersion = 0; // 0 means no UPD stored at client-side yet.
    /**
     * 0: Standard
     * 1: Deutsch, Code ‚de’ (German), Subset Deutsch, Codeset 1 (Latin 1)
     * 2: Englisch, Code ‚en’ (English), Subset Englisch, Codeset 1 (Latin 1)
     * 3: Französisch, Code ‚fr’ (French), Subset Französisch, Codeset 1 (Latin 1)
     */
    public int $dialogsprache = 0; // The bank's default is fine.
    /** Max length: 25 */
    public string $produktbezeichnung;

    /** Max length: 5 */
    public string $produktversion;

    public static function create(FinTsOptions $finTsOptions, ?BPD $bpd, ?UPD $upd): HKVVBv3
    {
        $hkvvBv3 = HKVVBv3::createEmpty();
        $hkvvBv3->bpdVersion = $bpd instanceof \BytesCommerce\Protocol\BPD ? $bpd->getVersion() : 0;
        $hkvvBv3->updVersion = $upd instanceof \BytesCommerce\Protocol\UPD ? $upd->getVersion() : 0;
        $hkvvBv3->produktbezeichnung = $finTsOptions->productName;
        $hkvvBv3->produktversion = $finTsOptions->productVersion;
        return $hkvvBv3;
    }
}

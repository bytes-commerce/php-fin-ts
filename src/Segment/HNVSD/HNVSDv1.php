<?php

declare(strict_types=1);



namespace BytesCommerce\Segment\HNVSD;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Syntax\Bin;

/**
 * Segment: Verschlüsselte Daten (Version 1)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Security_Sicherheitsverfahren_HBCI_Rel_20181129_final_version.pdf
 * Section: B.5.4
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Formals_2017-10-06_final_version.pdf
 * Section B.8
 */
class HNVSDv1 extends BaseSegment
{
    /**
     * The specification demands that HNVSD always has segment number 998. See section B.8.
     */
    public const SEGMENT_NUMBER = 999;

    /**
     * Note: This field is called "encrypted", but for PIN/TAN it contains plaintext data anyway, because the encryption
     * is done in the transport layer (TLS).
     */
    public Bin $datenVerschluesselt;

    /**
     * @param BaseSegment[] $segments Some segments that will be serialized into the data field.
     */
    public static function create(array $segments): HNVSDv1
    {
        $hnvsDv1 = HNVSDv1::createEmpty();
        $hnvsDv1->segmentkopf->segmentnummer = static::SEGMENT_NUMBER;

        $data = '';
        foreach ($segments as $segment) {
            $data .= $segment->serialize();
        }

        $hnvsDv1->datenVerschluesselt = new Bin($data);
        return $hnvsDv1;
    }
}

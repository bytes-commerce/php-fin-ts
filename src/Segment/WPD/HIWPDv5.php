<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\WPD;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Syntax\Bin;

/**
 * Segment: Depotaufstellung Kreditinstitutsrückmledung (Version 5)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: C.4.3.1b
 */
class HIWPDv5 extends BaseSegment implements HIWPD
{
    /** Uses SWIFT format MT353, version SRG 1998 */
    public Bin $depotaufstellung;

    public function getDepotaufstellung(): Bin
    {
        return $this->depotaufstellung;
    }
}

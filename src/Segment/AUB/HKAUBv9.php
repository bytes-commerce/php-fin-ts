<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\AUB;

use BytesCommerce\Segment\BaseSegment;

/**
 * Segment: Auslandsüberweisung
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: C.5.1.4 a)
 */
class HKAUBv9 extends BaseSegment
{
    public \BytesCommerce\Segment\Common\Kti $kontoverbindungInternational;

    /** Max length: 4 */
    public int $DTAZVHandbuch;

    public \BytesCommerce\Syntax\Bin $DTAZVDatensatz;
}

<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\CSE;

use BytesCommerce\Segment\BaseSegment;

/**
 * Segment: SEPA Einzelüberweisung Parameter (Version 1)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: C.10.2.1 c)
 */
class HICSEv1 extends BaseSegment
{
    public string $auftragsidentifikation;
}

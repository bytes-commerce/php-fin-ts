<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\AUB;

use BytesCommerce\Segment\BaseGeschaeftsvorfallparameter;

/**
 * Segment: Auslandsüberweisung Parameter
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: C.5.1.4 c)
 */
class HIAUBSv9 extends BaseGeschaeftsvorfallparameter
{
    public ParameterAuslandsueberweisungV2 $parameter;
}

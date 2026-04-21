<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\VPP;

use BytesCommerce\Segment\BaseGeschaeftsvorfallparameter;

/**
 * Segment: Namensabgleich Prüfauftrag Parameter
 *
 * @see FinTS_3.0_Messages_Geschaeftsvorfaelle_VOP_1.01_2025_06_27_FV.pdf
 * Section: C.10.7.1 c)
 */
class HIVPPSv1 extends BaseGeschaeftsvorfallparameter
{
    public ParameterNamensabgleichPruefauftragV1 $parameter;
}

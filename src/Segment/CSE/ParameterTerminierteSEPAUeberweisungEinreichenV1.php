<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\CSE;

use BytesCommerce\Segment\BaseDeg;

class ParameterTerminierteSEPAUeberweisungEinreichenV1 extends BaseDeg
{
    /** Must be => 1 */
    public int $minimaleVorlaufzeit;

    public int $maximaleVorlaufzeit;
}

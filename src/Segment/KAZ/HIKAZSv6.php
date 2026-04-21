<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\KAZ;

use BytesCommerce\Segment\BaseGeschaeftsvorfallparameter;

/**
 * Segment: Kontoumsätze/Zeitraum Parameter (Version 6)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: C.2.1.1.1.1 c)
 */
class HIKAZSv6 extends BaseGeschaeftsvorfallparameter implements HIKAZS
{
    public ParameterKontoumsaetzeV2 $parameter;

    public function getParameter(): ParameterKontoumsaetze
    {
        return $this->parameter;
    }
}

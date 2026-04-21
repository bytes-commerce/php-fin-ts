<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\DME;

use BytesCommerce\Segment\BaseGeschaeftsvorfallparameter;
use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\DSE\HIDXES;
use BytesCommerce\Segment\DSE\SEPADirectDebitMinimalLeadTimeProvider;

/**
 * Segment: Terminierte SEPA-Sammellastschrift einreichen Parameter
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: C.10.3.2.2.2 c)
 */
class HIDMESv2 extends BaseGeschaeftsvorfallparameter implements HIDXES
{
    public ParameterTerminierteSEPASammellastschriftEinreichenV2 $parameter;

    public function getParameter(): SEPADirectDebitMinimalLeadTimeProvider
    {
        return $this->parameter;
    }

    public function createRequestSegment(): BaseSegment
    {
        return HKDMEv2::createEmpty();
    }
}

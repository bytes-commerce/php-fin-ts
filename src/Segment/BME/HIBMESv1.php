<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\BME;

use BytesCommerce\Segment\BaseGeschaeftsvorfallparameter;
use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\DSE\HIDXES;
use BytesCommerce\Segment\DSE\SEPADirectDebitMinimalLeadTimeProvider;

/**
 * Segment: Terminierte SEPA-Sammellastschrift einreichen Parameter
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: C.10.3.3.2.1 c)
 */
class HIBMESv1 extends BaseGeschaeftsvorfallparameter implements HIDXES
{
    public ParameterTerminierteSEPAFirmenSammellastschriftEinreichenV1 $parameter;

    public function getParameter(): SEPADirectDebitMinimalLeadTimeProvider
    {
        return $this->parameter;
    }

    public function createRequestSegment(): BaseSegment
    {
        return HKBMEv1::createEmpty();
    }
}

<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\BSE;

use BytesCommerce\Segment\BaseDeg;
use BytesCommerce\Segment\DSE\MinimaleVorlaufzeitSEPALastschrift;
use BytesCommerce\Segment\DSE\SEPADirectDebitMinimalLeadTimeProvider;

class ParameterTerminierteSEPAFirmenEinzellastschriftEinreichenV1 extends BaseDeg implements SEPADirectDebitMinimalLeadTimeProvider
{
    /** Must be => 1 */
    public int $minimaleVorlaufzeitFNALRCUR;

    public int $maximaleVorlaufzeitFNALRCUR;

    /** Must be => 1 */
    public int $minimaleVorlaufzeitFRSTOOFF;

    public int $maximaleVorlaufzeitFRSTOOFF;

    public function getMinimalLeadTime(string $seqType): ?MinimaleVorlaufzeitSEPALastschrift
    {
        $leadTime = in_array($seqType, ['FRST', 'OOFF'], true) ? $this->minimaleVorlaufzeitFRSTOOFF : $this->minimaleVorlaufzeitFNALRCUR;
        return MinimaleVorlaufzeitSEPALastschrift::create($leadTime, '235959');
    }
}

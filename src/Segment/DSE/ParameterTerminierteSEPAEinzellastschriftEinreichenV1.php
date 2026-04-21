<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\DSE;

use BytesCommerce\Segment\BaseDeg;

class ParameterTerminierteSEPAEinzellastschriftEinreichenV1 extends BaseDeg implements SEPADirectDebitMinimalLeadTimeProvider
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

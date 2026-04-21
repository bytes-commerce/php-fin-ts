<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\BSE;

use BytesCommerce\Segment\BaseDeg;
use BytesCommerce\Segment\DSE\MinimaleVorlaufzeitSEPALastschrift;
use BytesCommerce\Segment\DSE\SEPADirectDebitMinimalLeadTimeProvider;

abstract class ParameterTerminierteSEPAFirmenLastschriftEinreichenV2 extends BaseDeg implements SEPADirectDebitMinimalLeadTimeProvider
{
    public string $minimaleVorlaufzeitCodiert;

    public string $maximaleVorlaufzeitCodiert;

    public function getMinimaleVorlaufzeitCodiert(): string
    {
        return $this->minimaleVorlaufzeitCodiert;
    }

    public function getMaximaleVorlaufzeitCodiert(): string
    {
        return $this->maximaleVorlaufzeitCodiert;
    }

    /** @return MinimaleVorlaufzeitSEPALastschrift[] */
    public function getMinimalLeadTime(string $seqType): array
    {
        return array_map(fn(array $value) => $value[$seqType] ?? null, MinimaleVorlaufzeitSEPALastschrift::parseCodedB2B($this->minimaleVorlaufzeitCodiert));
    }
}

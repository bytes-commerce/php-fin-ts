<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\DSE;

use BytesCommerce\Segment\BaseDeg;

abstract class ParameterTerminierteSEPALastschriftEinreichenV2 extends BaseDeg implements SEPADirectDebitMinimalLeadTimeProvider
{
    public string $minimaleVorlaufzeitCodiert;

    public string $maximaleVorlaufzeitCodiert;

    public function getMinimalLeadTime(string $seqType): array
    {
        return array_map(fn(array $value) => $value[$seqType] ?? null, MinimaleVorlaufzeitSEPALastschrift::parseCoded($this->minimaleVorlaufzeitCodiert));
    }
}

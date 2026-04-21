<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\DSE;

interface SEPADirectDebitMinimalLeadTimeProvider
{
    /** @return MinimaleVorlaufzeitSEPALastschrift|MinimaleVorlaufzeitSEPALastschrift[]|null*/
    public function getMinimalLeadTime(string $seqType): MinimaleVorlaufzeitSEPALastschrift|array|null;
}

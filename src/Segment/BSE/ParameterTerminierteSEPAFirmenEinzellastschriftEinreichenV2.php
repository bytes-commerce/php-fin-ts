<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\BSE;

use BytesCommerce\Segment\UnterstuetzteSEPADatenformate;
use BytesCommerce\Segment\UnterstuetzteSEPADatenformateTrait;

class ParameterTerminierteSEPAFirmenEinzellastschriftEinreichenV2 extends ParameterTerminierteSEPAFirmenLastschriftEinreichenV2 implements UnterstuetzteSEPADatenformate
{
    use UnterstuetzteSEPADatenformateTrait;

    /** Max Length: 4096 */
    public ?string $zulaessigePurposecodes = null;

    /** @var string[]|null @Max(9) Max length: 256 */
    public ?array $unterstuetzteSEPADatenformate = null;
}

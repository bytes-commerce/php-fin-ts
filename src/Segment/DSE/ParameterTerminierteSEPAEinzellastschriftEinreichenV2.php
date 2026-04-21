<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\DSE;

use BytesCommerce\Segment\UnterstuetzteSEPADatenformate;
use BytesCommerce\Segment\UnterstuetzteSEPADatenformateTrait;

class ParameterTerminierteSEPAEinzellastschriftEinreichenV2 extends ParameterTerminierteSEPALastschriftEinreichenV2 implements UnterstuetzteSEPADatenformate
{
    use UnterstuetzteSEPADatenformateTrait;

    /** Max Length: 4096 */
    public ?string $zulaessigePurposecodes = null;

    /** @var string[]|null @Max(9) Max length: 256 */
    public ?array $unterstuetzteSEPADatenformate = null;
}

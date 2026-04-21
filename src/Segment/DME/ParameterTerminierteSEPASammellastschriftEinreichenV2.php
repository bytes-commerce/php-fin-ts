<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\DME;

use BytesCommerce\Segment\DSE\ParameterTerminierteSEPALastschriftEinreichenV2;
use BytesCommerce\Segment\UnterstuetzteSEPADatenformate;
use BytesCommerce\Segment\UnterstuetzteSEPADatenformateTrait;

class ParameterTerminierteSEPASammellastschriftEinreichenV2 extends ParameterTerminierteSEPALastschriftEinreichenV2 implements UnterstuetzteSEPADatenformate
{
    use UnterstuetzteSEPADatenformateTrait;

    public int $maximaleAnzahlDirectDebitTransferTransactionInformation;

    public bool $summenfeldBenoetigt;

    public bool $einzelbuchungErlaubt;

    /** Max Length: 4096 */
    public ?string $zulaessigePurposecodes = null;

    /** @var string[]|null @Max(9) Max Length: 256 */
    public ?array $unterstuetzteSEPADatenformate = null;
}

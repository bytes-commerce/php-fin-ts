<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\BME;

use BytesCommerce\Segment\BSE\ParameterTerminierteSEPAFirmenLastschriftEinreichenV2;
use BytesCommerce\Segment\UnterstuetzteSEPADatenformate;
use BytesCommerce\Segment\UnterstuetzteSEPADatenformateTrait;

class ParameterTerminierteSEPAFirmenSammellastschriftEinreichenV2 extends ParameterTerminierteSEPAFirmenLastschriftEinreichenV2 implements UnterstuetzteSEPADatenformate
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

<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\BME;

use BytesCommerce\Segment\BSE\ParameterTerminierteSEPAFirmenEinzellastschriftEinreichenV1;

class ParameterTerminierteSEPAFirmenSammellastschriftEinreichenV1 extends ParameterTerminierteSEPAFirmenEinzellastschriftEinreichenV1
{
    public int $maximaleAnzahlDirectDebitTransferTransactionInformation;

    public bool $summenfeldBenoetigt;

    public bool $einzelbuchungErlaubt;
}

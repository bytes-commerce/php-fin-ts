<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\DME;

use BytesCommerce\Segment\DSE\ParameterTerminierteSEPAEinzellastschriftEinreichenV1;

class ParameterTerminierteSEPASammellastschriftEinreichenV1 extends ParameterTerminierteSEPAEinzellastschriftEinreichenV1
{
    public int $maximaleAnzahlDirectDebitTransferTransactionInformation;

    public bool $summenfeldBenoetigt;

    public bool $einzelbuchungErlaubt;
}

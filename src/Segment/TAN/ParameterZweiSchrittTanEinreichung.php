<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\TAN;

use BytesCommerce\Model\TanMode;

interface ParameterZweiSchrittTanEinreichung
{
    public function isEinschrittVerfahrenErlaubt(): bool;

    /** @return TanMode[] */
    public function getVerfahrensparameterZweiSchrittVerfahren(): array;
}

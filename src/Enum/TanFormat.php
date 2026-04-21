<?php

declare(strict_types=1);

/** @noinspection PhpUnused */

namespace BytesCommerce\Enum;

/**
 * TAN format types.
 */
enum TanFormat: int
{
    case Numerical = 1;
    case Alphanumerical = 2;
}
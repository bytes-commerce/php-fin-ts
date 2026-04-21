<?php

declare(strict_types=1);

/** @noinspection PhpUnused */

namespace BytesCommerce\Enum;

/**
 * SEPA Direct Debit types.
 */
enum DirectDebitType: string
{
    case Core = 'CORE';
    case Cor1 = 'COR1';
    case B2b = 'B2B';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }
}
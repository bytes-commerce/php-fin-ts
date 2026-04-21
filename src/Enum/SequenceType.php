<?php

declare(strict_types=1);

/** @noinspection PhpUnused */

namespace BytesCommerce\Enum;

/**
 * SEPA Direct Debit sequence types.
 */
enum SequenceType: string
{
    case First = 'FRST';
    case OneOff = 'OOFF';
    case Final = 'FNAL';
    case Recurring = 'RCUR';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }
}
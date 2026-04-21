<?php

declare(strict_types=1);

namespace BytesCommerce\Util;

/**
 * Factory for generating random numbers.
 * This abstraction allows for deterministic random numbers in tests via dependency injection.
 */
class RandomNumberFactory
{
    /**
     * Generate a cryptographically secure random integer between $min and $max (inclusive).
     */
    public function randomInt(int $min, int $max): int
    {
        return random_int($min, $max);
    }

    /**
     * Generate a random 7-digit reference number (between 1000000 and 9999999).
     * Used for message signature references in FinTS protocol.
     */
    public function generateSignatureReference(): string
    {
        return strval($this->randomInt(1000000, 9999999));
    }
}
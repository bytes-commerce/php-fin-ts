<?php

declare(strict_types=1);

/** @noinspection PhpUnused */

namespace BytesCommerce\Exception;

/**
 * Thrown when authentication fails (invalid credentials, locked account, etc.).
 */
class AuthenticationException extends FinTsException
{
}
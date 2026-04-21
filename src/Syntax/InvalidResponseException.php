<?php

declare(strict_types=1);

namespace BytesCommerce\Syntax;

/**
 * Thrown if the server responds with a syntactically invalid message, or at least one that this library fails to parse.
 */
class InvalidResponseException extends \RuntimeException
{
    public function __construct(string $message, int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

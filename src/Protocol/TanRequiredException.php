<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Protocol;

use BytesCommerce\Model\TanRequest;

/**
 * Thrown when an action result is read, but it is not available because the action requires a TAN to be completed.
 */
class TanRequiredException extends \RuntimeException
{
    public function __construct(private TanRequest $tanRequest)
    {
        parent::__construct('This action requires a TAN to be completed.');
    }

    public function getTanRequest()
    {
        return $this->tanRequest;
    }
}

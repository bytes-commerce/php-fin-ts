<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Protocol;

use BytesCommerce\Model\PollingInfo;

/**
 * Thrown when an action result is read, but the action is still pending a long-running operation on the server and
 * requires polling to find out when it's completed.
 */
class ActionPendingException extends \RuntimeException
{
    public function __construct(private PollingInfo $pollingInfo)
    {
        parent::__construct('This action needs polling to await finishing a server-side operation.');
    }

    public function getPollingInfo(): PollingInfo
    {
        return $this->pollingInfo;
    }
}

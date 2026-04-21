<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Protocol;

use BytesCommerce\Model\VopConfirmationRequest;

/**
 * Thrown when an action result is read, but the action is still pending the user's confirmation of the Verification of
 * Payee result.
 */
class VopConfirmationRequiredException extends \RuntimeException
{
    public function __construct(private VopConfirmationRequest $vopConfirmationRequest)
    {
        parent::__construct('This action needs VOP confirmation before it will be executed.');
    }

    public function getVopConfirmationRequest(): VopConfirmationRequest
    {
        return $this->vopConfirmationRequest;
    }
}

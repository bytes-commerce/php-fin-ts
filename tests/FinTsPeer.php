<?php

namespace BytesCommerce\tests;

use BytesCommerce\Connection;
use BytesCommerce\FinTs;
use BytesCommerce\Options\Credentials;
use BytesCommerce\Options\FinTsOptions;
use BytesCommerce\Protocol\ServerException;

/**
 * Sub-classes {@link FinTs} to expose some of the protected functions, and also to inject the Connection mock.
 */
class FinTsPeer extends FinTs
{
    public static ?Connection $mockConnection = null;

    public function __construct(FinTsOptions $options, ?Credentials $credentials)
    {
        parent::__construct($options, $credentials);
    }

    protected function newConnection(): Connection
    {
        return self::$mockConnection;
    }

    /**
     * @throws ServerException
     */
    public function endDialog(bool $isAnonymous = false): void // parent::endDialog() is protected
    {
        parent::endDialog($isAnonymous);
    }

    public function getDialogId(): ?string
    {
        return $this->dialogId;
    }
}

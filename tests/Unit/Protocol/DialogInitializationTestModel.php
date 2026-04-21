<?php

namespace BytesCommerce\Tests\Unit\Protocol;

use BytesCommerce\Options\Credentials;
use BytesCommerce\Options\FinTsOptions;
use BytesCommerce\Protocol\DialogInitialization;

class DialogInitializationTestModel extends DialogInitialization
{
    public function __construct(string $kundensystemId, string $needTanForSegment)
    {
        parent::__construct(
            new FinTsOptions(),
            Credentials::create('user', 'password'),
            null,
            '',
            $kundensystemId,
            null,
        );

        $this->needTanForSegment = $needTanForSegment;
    }

    public function needsTan(): bool
    {
        return true;
    }
}

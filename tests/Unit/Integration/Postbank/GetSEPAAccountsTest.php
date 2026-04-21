<?php

namespace BytesCommerce\Tests\Unit\Integration\Postbank;

class GetSEPAAccountsTest extends PostbankIntegrationTestBase
{
    public const GET_ACCOUNTS_REQUEST = "HKSPA:3:1'";

    public const EMPTY_ACCOUNTS_RESPONSE = "HIRMG:2:2+3060::Teilweise liegen Warnungen/Hinweise vor.'HIRMS:3:2:3+3010::Es liegen keine Eintrage vor.'";

    /**
     * @see https://github.com/nemiah/phpFinTS/issues/231#issuecomment-583286097
     * @throws \Throwable
     */
    public function testGetSEPAAccountsEmptyResponse(): void
    {
        $this->initDialog();

        $this->expectMessage(static::GET_ACCOUNTS_REQUEST, static::EMPTY_ACCOUNTS_RESPONSE);
        $getSEPAAccounts = new \BytesCommerce\Action\GetSEPAAccounts();
        $this->fints->execute($getSEPAAccounts);
        $accounts = $getSEPAAccounts->getAccounts();

        $this->assertEmpty($accounts);
    }
}

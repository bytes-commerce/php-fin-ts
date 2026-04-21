<?php

namespace BytesCommerce\Tests\Unit\Integration\Postbank;

class InitEndDialogTest extends PostbankIntegrationTestBase
{
    /**
     * @throws \BytesCommerce\Protocol\ServerException
     * @throws \Throwable
     */
    public function testInitAndEndDialog(): void
    {
        $this->initDialog();
        $this->assertNotNull($this->fints->getDialogId());
        $this->expectMessage(static::FINAL_END_REQUEST, static::FINAL_END_RESPONSE);
        $this->fints->endDialog();
    }
}

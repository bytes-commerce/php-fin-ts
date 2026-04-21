<?php

namespace BytesCommerce\Tests\Unit\Integration\IngDiba;

class InitEndDialogTest extends IngDibaIntegrationTestBase
{
    /**
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

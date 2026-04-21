<?php

namespace BytesCommerce\Tests\Unit;

use BytesCommerce\Tests\Unit\Integration\DKB\SendSEPATransferTest as DKBSendSEPATransferTest;

class BaseActionTanSerializationTest extends DKBSendSEPATransferTest
{
    /**
     * @throws \Throwable
     */
    public function testSerializesTanRequest(): void
    {
        // We piggy-back on the DKB integration test to provide an action that has some reasonable data inside and that
        // has already been executed so that a TAN request is present.
        $this->initDialog();
        $this->expectMessage($this->getSendTransferRequest(), static::SEND_TRANSFER_RESPONSE);
        $sendSEPATransfer = $this->runInitialRequest();

        // Sanity-check that the TAN request is present.
        $this->assertNotNull($sendSEPATransfer->getTanRequest());
        $this->assertNotNull($sendSEPATransfer->getNeedTanForSegment());

        // Do a serialization roundtrip.
        $serializedAction = serialize($sendSEPATransfer);
        $unserializedAction = unserialize($serializedAction);

        // Verify that the TAN request hasn't changed.
        $this->assertEquals($sendSEPATransfer->getTanRequest(), $unserializedAction->getTanRequest());
        $this->assertEquals($sendSEPATransfer->getNeedTanForSegment(), $unserializedAction->getNeedTanForSegment());
    }
}

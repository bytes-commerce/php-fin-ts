<?php

namespace BytesCommerce\Tests\Unit\Action;

use BytesCommerce\Protocol\Message;
use BytesCommerce\Syntax\Serializer;
use BytesCommerce\Tests\Unit\Integration\DKB\SendSEPATransferTest as DKBSendSEPATransferTest;


class SendSEPATransferTest extends DKBSendSEPATransferTest
{
    /**
     * @throws \Throwable
     */
    public function testSerializesRequest(): void
    {
        // We piggy-back on the DKB integration test to provide an action that has some reasonable data inside and that
        // has already been executed so that a TAN is now required, which allows the action to be serialized.
        $this->initDialog();
        $this->expectMessage($this->getSendTransferRequest(), static::SEND_TRANSFER_RESPONSE);
        $sendSEPATransfer = $this->runInitialRequest();

        // The key behavior we need is that the action produces the same request again after (un)serialization. It is
        // not necessary for us to check the field values inside the action directly.
        $originalRequest = $sendSEPATransfer->getNextRequest($this->fints->getBpd(), null);
        $this->assertIsArray($originalRequest);
        Message::setSegmentNumbers($originalRequest, 42);

        // Sanity-check that the request we're getting is something sensible (not empty string or so).
        $serializedRequest = Serializer::serializeSegments($originalRequest);
        $this->assertStringContainsString('HKCCS', $serializedRequest);
        $this->assertStringContainsString('DE42000000001234567890', $serializedRequest);

        // Do a serialization roundtrip.
        $serializedAction = serialize($sendSEPATransfer);
        $unserializedAction = unserialize($serializedAction);

        // Verify that the request is still the same.
        $newRequest = $unserializedAction->getNextRequest($this->fints->getBpd(), null);
        $this->assertIsArray($newRequest);
        Message::setSegmentNumbers($newRequest, 42);
        $this->assertEquals($originalRequest, $newRequest);
    }
}

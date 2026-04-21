<?php

namespace BytesCommerce\Tests\Unit;

use BytesCommerce\Tests\Unit\Integration\Atruvia\SendTransferVoPTest;

class BaseActionVopSerializationTest extends SendTransferVoPTest
{
    /**
     * @throws \Throwable
     */
    public function testSerializesPollingInfo(): void
    {
        // We piggy-back on the Atruvia integration test to provide an action that has some reasonable data inside and
        // has already been executed so that polling is now required.
        $this->initDialog();
        $sendSEPATransfer = $this->createAction();
        $this->expectMessage(static::SEND_TRANSFER_REQUEST, mb_convert_encoding(static::SEND_TRANSFER_RESPONSE_POLLING_NEEDED, 'ISO-8859-1', 'UTF-8'));
        $this->fints->execute($sendSEPATransfer);

        // Sanity-check that the polling is now expected.
        $this->assertNotNull($sendSEPATransfer->getPollingInfo());

        // Do a serialization roundtrip.
        $serializedAction = serialize($sendSEPATransfer);
        $unserializedAction = unserialize($serializedAction);

        // Verify that the polling info is still the same.
        $this->assertEquals($sendSEPATransfer->getPollingInfo(), $unserializedAction->getPollingInfo());
    }

    /**
     * @throws \Throwable
     */
    public function testSerializesVopConfirmationRequest(): void
    {
        // We piggy-back on the Atruvia integration test to provide an action that has some reasonable data inside and
        // has already been executed so that polling is now required.
        $this->initDialog();
        $sendSEPATransfer = $this->createAction();
        $this->expectMessage(static::SEND_TRANSFER_REQUEST, mb_convert_encoding(static::SEND_TRANSFER_RESPONSE_POLLING_NEEDED, 'ISO-8859-1', 'UTF-8'));
        $response = static::buildVopReportResponse(static::VOP_REPORT_PARTIAL_MATCH_RESPONSE, static::VOP_REPORT_PARTIAL_MATCH_XML_PAYLOAD);
        $this->expectMessage(static::POLL_VOP_REQUEST, $response);
        $this->fints->execute($sendSEPATransfer);
        $this->assertTrue($sendSEPATransfer->needsPollingWait());
        $this->fints->pollAction($sendSEPATransfer);

        // Sanity-check that the VOP confirmation is now expected.
        $this->assertNotNull($sendSEPATransfer->getVopConfirmationRequest());

        // Do a serialization roundtrip.
        $serializedAction = serialize($sendSEPATransfer);
        $unserializedAction = unserialize($serializedAction);

        // Verify that the polling info is still the same.
        $this->assertEquals($sendSEPATransfer->getVopConfirmationRequest(), $unserializedAction->getVopConfirmationRequest());
    }
}

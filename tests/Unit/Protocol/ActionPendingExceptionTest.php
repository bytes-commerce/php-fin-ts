<?php

namespace BytesCommerce\Tests\Unit\Protocol;

use BytesCommerce\Model\PollingInfo;
use BytesCommerce\Protocol\ActionPendingException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for ActionPendingException class.
 * Tests the exception thrown when an action is pending polling.
 */
class ActionPendingExceptionTest extends TestCase
{
    private PollingInfo&MockObject $pollingInfo;

    private ActionPendingException $actionPendingException;

    protected function setUp(): void
    {
        $this->pollingInfo = $this->createMock(PollingInfo::class);
        $this->actionPendingException = new ActionPendingException($this->pollingInfo);
    }

    public function testConstructorSetsMessage(): void
    {
        $this->assertSame('This action needs polling to await finishing a server-side operation.', $this->actionPendingException->getMessage());
    }

    public function testConstructorSetsPollingInfo(): void
    {
        $this->assertSame($this->pollingInfo, $this->actionPendingException->getPollingInfo());
    }

    public function testInheritsFromRuntimeException(): void
    {
        $this->assertInstanceOf(\RuntimeException::class, $this->actionPendingException);
    }

    public function testGetMessageReturnsCorrectString(): void
    {
        $actionPendingException = new ActionPendingException($this->pollingInfo);
        $this->assertSame('This action needs polling to await finishing a server-side operation.', $actionPendingException->getMessage());
    }

    public function testGetPollingInfoReturnsSameInstance(): void
    {
        $pollingInfo = $this->createMock(PollingInfo::class);
        $actionPendingException = new ActionPendingException($pollingInfo);

        $this->assertSame($pollingInfo, $actionPendingException->getPollingInfo());
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new ActionPendingException($this->pollingInfo);
        } catch (\RuntimeException $runtimeException) {
            $this->assertInstanceOf(ActionPendingException::class, $runtimeException);
        }
    }

    public function testPollingInfoCanBeMocked(): void
    {
        $pollingInfo = $this->createMock(PollingInfo::class);
        $pollingInfo->method('getNextAttemptInSeconds')->willReturn(30);
        $pollingInfo->method('getInformationForUser')->willReturn('Processing...');

        $actionPendingException = new ActionPendingException($pollingInfo);

        $this->assertSame(30, $actionPendingException->getPollingInfo()->getNextAttemptInSeconds());
        $this->assertSame('Processing...', $actionPendingException->getPollingInfo()->getInformationForUser());
    }
}

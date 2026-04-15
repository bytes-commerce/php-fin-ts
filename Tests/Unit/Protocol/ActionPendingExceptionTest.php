<?php

namespace Fhp\Tests\Unit\Protocol;

use Fhp\Model\PollingInfo;
use Fhp\Protocol\ActionPendingException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for ActionPendingException class.
 * Tests the exception thrown when an action is pending polling.
 */
class ActionPendingExceptionTest extends TestCase
{
    private PollingInfo&MockObject $pollingInfo;
    private ActionPendingException $exception;

    protected function setUp(): void
    {
        $this->pollingInfo = $this->createMock(PollingInfo::class);
        $this->exception = new ActionPendingException($this->pollingInfo);
    }

    public function testConstructorSetsMessage(): void
    {
        $this->assertSame('This action needs polling to await finishing a server-side operation.', $this->exception->getMessage());
    }

    public function testConstructorSetsPollingInfo(): void
    {
        $this->assertSame($this->pollingInfo, $this->exception->getPollingInfo());
    }

    public function testInheritsFromRuntimeException(): void
    {
        $this->assertInstanceOf(\RuntimeException::class, $this->exception);
    }

    public function testGetMessageReturnsCorrectString(): void
    {
        $exception = new ActionPendingException($this->pollingInfo);
        $this->assertSame('This action needs polling to await finishing a server-side operation.', $exception->getMessage());
    }

    public function testGetPollingInfoReturnsSameInstance(): void
    {
        $pollingInfo = $this->createMock(PollingInfo::class);
        $exception = new ActionPendingException($pollingInfo);

        $this->assertSame($pollingInfo, $exception->getPollingInfo());
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new ActionPendingException($this->pollingInfo);
        } catch (\RuntimeException $e) {
            $this->assertInstanceOf(ActionPendingException::class, $e);
        }
    }

    public function testPollingInfoCanBeMocked(): void
    {
        $pollingInfo = $this->createMock(PollingInfo::class);
        $pollingInfo->method('getNextAttemptInSeconds')->willReturn(30);
        $pollingInfo->method('getInformationForUser')->willReturn('Processing...');

        $exception = new ActionPendingException($pollingInfo);

        $this->assertSame(30, $exception->getPollingInfo()->getNextAttemptInSeconds());
        $this->assertSame('Processing...', $exception->getPollingInfo()->getInformationForUser());
    }
}

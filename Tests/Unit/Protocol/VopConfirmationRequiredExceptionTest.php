<?php

namespace Fhp\Tests\Unit\Protocol;

use Fhp\Model\VopConfirmationRequest;
use Fhp\Protocol\VopConfirmationRequiredException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for VopConfirmationRequiredException class.
 * Tests the exception thrown when VOP confirmation is needed.
 */
class VopConfirmationRequiredExceptionTest extends TestCase
{
    private VopConfirmationRequest&MockObject $vopConfirmationRequest;
    private VopConfirmationRequiredException $exception;

    protected function setUp(): void
    {
        $this->vopConfirmationRequest = $this->createMock(VopConfirmationRequest::class);
        $this->exception = new VopConfirmationRequiredException($this->vopConfirmationRequest);
    }

    public function testConstructorSetsMessage(): void
    {
        $this->assertSame('This action needs VOP confirmation before it will be executed.', $this->exception->getMessage());
    }

    public function testConstructorSetsVopConfirmationRequest(): void
    {
        $this->assertSame($this->vopConfirmationRequest, $this->exception->getVopConfirmationRequest());
    }

    public function testInheritsFromRuntimeException(): void
    {
        $this->assertInstanceOf(\RuntimeException::class, $this->exception);
    }

    public function testGetMessageReturnsCorrectString(): void
    {
        $exception = new VopConfirmationRequiredException($this->vopConfirmationRequest);
        $this->assertSame('This action needs VOP confirmation before it will be executed.', $exception->getMessage());
    }

    public function testGetVopConfirmationRequestReturnsSameInstance(): void
    {
        $vopConfirmationRequest = $this->createMock(VopConfirmationRequest::class);
        $exception = new VopConfirmationRequiredException($vopConfirmationRequest);

        $this->assertSame($vopConfirmationRequest, $exception->getVopConfirmationRequest());
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new VopConfirmationRequiredException($this->vopConfirmationRequest);
        } catch (\RuntimeException $e) {
            $this->assertInstanceOf(VopConfirmationRequiredException::class, $e);
        }
    }
}

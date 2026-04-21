<?php

namespace BytesCommerce\Tests\Unit\Protocol;

use BytesCommerce\Model\VopConfirmationRequest;
use BytesCommerce\Protocol\VopConfirmationRequiredException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for VopConfirmationRequiredException class.
 * Tests the exception thrown when VOP confirmation is needed.
 */
class VopConfirmationRequiredExceptionTest extends TestCase
{
    private VopConfirmationRequest&MockObject $vopConfirmationRequest;

    private VopConfirmationRequiredException $vopConfirmationRequiredException;

    protected function setUp(): void
    {
        $this->vopConfirmationRequest = $this->createMock(VopConfirmationRequest::class);
        $this->vopConfirmationRequiredException = new VopConfirmationRequiredException($this->vopConfirmationRequest);
    }

    public function testConstructorSetsMessage(): void
    {
        $this->assertSame('This action needs VOP confirmation before it will be executed.', $this->vopConfirmationRequiredException->getMessage());
    }

    public function testConstructorSetsVopConfirmationRequest(): void
    {
        $this->assertSame($this->vopConfirmationRequest, $this->vopConfirmationRequiredException->getVopConfirmationRequest());
    }

    public function testInheritsFromRuntimeException(): void
    {
        $this->assertInstanceOf(\RuntimeException::class, $this->vopConfirmationRequiredException);
    }

    public function testGetMessageReturnsCorrectString(): void
    {
        $vopConfirmationRequiredException = new VopConfirmationRequiredException($this->vopConfirmationRequest);
        $this->assertSame('This action needs VOP confirmation before it will be executed.', $vopConfirmationRequiredException->getMessage());
    }

    public function testGetVopConfirmationRequestReturnsSameInstance(): void
    {
        $vopConfirmationRequest = $this->createMock(VopConfirmationRequest::class);
        $vopConfirmationRequiredException = new VopConfirmationRequiredException($vopConfirmationRequest);

        $this->assertSame($vopConfirmationRequest, $vopConfirmationRequiredException->getVopConfirmationRequest());
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new VopConfirmationRequiredException($this->vopConfirmationRequest);
        } catch (\RuntimeException $runtimeException) {
            $this->assertInstanceOf(VopConfirmationRequiredException::class, $runtimeException);
        }
    }
}

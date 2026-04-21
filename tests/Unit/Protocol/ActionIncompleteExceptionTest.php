<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Protocol;

use BytesCommerce\Protocol\ActionIncompleteException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for ActionIncompleteException class.
 * Tests the exception thrown when an action result is not yet available.
 */
class ActionIncompleteExceptionTest extends TestCase
{
    public function testConstructorSetsMessage(): void
    {
        $actionIncompleteException = new ActionIncompleteException();

        $this->assertSame('This action needs to be executed for the result to become available.', $actionIncompleteException->getMessage());
    }

    public function testInheritsFromRuntimeException(): void
    {
        $actionIncompleteException = new ActionIncompleteException();

        $this->assertInstanceOf(\RuntimeException::class, $actionIncompleteException);
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new ActionIncompleteException();
        } catch (\RuntimeException $runtimeException) {
            $this->assertInstanceOf(ActionIncompleteException::class, $runtimeException);
        }
    }

    public function testExceptionMessageIsConsistent(): void
    {
        $exception1 = new ActionIncompleteException();
        $exception2 = new ActionIncompleteException();

        $this->assertSame($exception1->getMessage(), $exception2->getMessage());
    }
}

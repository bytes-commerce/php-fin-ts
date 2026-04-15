<?php

namespace Fhp\Tests\Unit\Protocol;

use Fhp\Protocol\ActionIncompleteException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for ActionIncompleteException class.
 * Tests the exception thrown when an action result is not yet available.
 */
class ActionIncompleteExceptionTest extends TestCase
{
    public function testConstructorSetsMessage(): void
    {
        $exception = new ActionIncompleteException();

        $this->assertSame('This action needs to be executed for the result to become available.', $exception->getMessage());
    }

    public function testInheritsFromRuntimeException(): void
    {
        $exception = new ActionIncompleteException();

        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new ActionIncompleteException();
        } catch (\RuntimeException $e) {
            $this->assertInstanceOf(ActionIncompleteException::class, $e);
        }
    }

    public function testExceptionMessageIsConsistent(): void
    {
        $exception1 = new ActionIncompleteException();
        $exception2 = new ActionIncompleteException();

        $this->assertSame($exception1->getMessage(), $exception2->getMessage());
    }
}

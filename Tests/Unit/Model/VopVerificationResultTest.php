<?php

namespace Fhp\Tests\Unit\Model;

use Fhp\Model\VopVerificationResult;
use Fhp\Protocol\UnexpectedResponseException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for VopVerificationResult class.
 * Tests the parse() static method for all known verification result codes.
 */
class VopVerificationResultTest extends TestCase
{
    public function testParseWithCompletedFullMatch(): void
    {
        $result = VopVerificationResult::parse('RCVC');
        $this->assertSame(VopVerificationResult::CompletedFullMatch, $result);
    }

    public function testParseWithCompletedCloseMatch(): void
    {
        $result = VopVerificationResult::parse('RVMC');
        $this->assertSame(VopVerificationResult::CompletedCloseMatch, $result);
    }

    public function testParseWithCompletedNoMatch(): void
    {
        $result = VopVerificationResult::parse('RVNM');
        $this->assertSame(VopVerificationResult::CompletedNoMatch, $result);
    }

    public function testParseWithCompletedPartialMatch(): void
    {
        $result = VopVerificationResult::parse('RVCM');
        $this->assertSame(VopVerificationResult::CompletedPartialMatch, $result);
    }

    public function testParseWithNotApplicable(): void
    {
        $result = VopVerificationResult::parse('RVNA');
        $this->assertSame(VopVerificationResult::NotApplicable, $result);
    }

    public function testParseWithNullReturnsNull(): void
    {
        $result = VopVerificationResult::parse(null);
        $this->assertNull($result);
    }

    public function testParseWithUnknownCodeThrowsUnexpectedResponseException(): void
    {
        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage('Unexpected VOP result code: UNKNOWN');
        VopVerificationResult::parse('UNKNOWN');
    }

    public function testParseWithEmptyStringThrowsUnexpectedResponseException(): void
    {
        $this->expectException(UnexpectedResponseException::class);
        VopVerificationResult::parse('');
    }

    public function testParseWithLowercaseCodeThrowsUnexpectedResponseException(): void
    {
        $this->expectException(UnexpectedResponseException::class);
        VopVerificationResult::parse('rcvc');
    }

    public function testParseWithMixedCaseCodeThrowsUnexpectedResponseException(): void
    {
        $this->expectException(UnexpectedResponseException::class);
        VopVerificationResult::parse('Rcvc');
    }

    public function testParseWithNumericCodeThrowsUnexpectedResponseException(): void
    {
        $this->expectException(UnexpectedResponseException::class);
        VopVerificationResult::parse('12345');
    }

    public function testParseWithSpecialCharactersThrowsUnexpectedResponseException(): void
    {
        $this->expectException(UnexpectedResponseException::class);
        VopVerificationResult::parse('RCVC!');
    }

    public function testParseWithWhitespaceThrowsUnexpectedResponseException(): void
    {
        $this->expectException(UnexpectedResponseException::class);
        VopVerificationResult::parse(' RCVC');
    }

    public function testConstantValuesAreCorrect(): void
    {
        $this->assertSame('CompletedFullMatch', VopVerificationResult::CompletedFullMatch);
        $this->assertSame('CompletedCloseMatch', VopVerificationResult::CompletedCloseMatch);
        $this->assertSame('CompletedNoMatch', VopVerificationResult::CompletedNoMatch);
        $this->assertSame('CompletedPartialMatch', VopVerificationResult::CompletedPartialMatch);
        $this->assertSame('NotApplicable', VopVerificationResult::NotApplicable);
    }

    public function testParseAllValidCodes(): void
    {
        $this->assertSame('CompletedFullMatch', VopVerificationResult::parse('RCVC'));
        $this->assertSame('CompletedCloseMatch', VopVerificationResult::parse('RVMC'));
        $this->assertSame('CompletedNoMatch', VopVerificationResult::parse('RVNM'));
        $this->assertSame('CompletedPartialMatch', VopVerificationResult::parse('RVCM'));
        $this->assertSame('NotApplicable', VopVerificationResult::parse('RVNA'));
    }
}

<?php

namespace Fhp\Tests\Unit\Model;

use Fhp\Model\NoPsd2TanMode;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for NoPsd2TanMode class.
 * Tests the placeholder TanMode implementation for banks without PSD2 support.
 */
class NoPsd2TanModeTest extends TestCase
{
    private NoPsd2TanMode $tanMode;

    protected function setUp(): void
    {
        $this->tanMode = new NoPsd2TanMode();
    }

    public function testGetIdReturnsNegativeOne(): void
    {
        $this->assertSame(-1, $this->tanMode->getId());
    }

    public function testGetNameReturnsNoPsd2Message(): void
    {
        $this->assertSame('No PSD2/TANs supported', $this->tanMode->getName());
    }

    public function testIsProzessvariante2ReturnsFalse(): void
    {
        $this->assertFalse($this->tanMode->isProzessvariante2());
    }

    public function testIsDecoupledReturnsFalse(): void
    {
        $this->assertFalse($this->tanMode->isDecoupled());
    }

    public function testGetChallengeLabelReturnsEmptyString(): void
    {
        $this->assertSame('', $this->tanMode->getChallengeLabel());
    }

    public function testGetMaxChallengeLengthReturnsZero(): void
    {
        $this->assertSame(0, $this->tanMode->getMaxChallengeLength());
    }

    public function testGetMaxTanLengthReturnsZero(): void
    {
        $this->assertSame(0, $this->tanMode->getMaxTanLength());
    }

    public function testGetTanFormatReturnsZero(): void
    {
        $this->assertSame(0, $this->tanMode->getTanFormat());
    }

    public function testNeedsTanMediumReturnsFalse(): void
    {
        $this->assertFalse($this->tanMode->needsTanMedium());
    }

    public function testGetSmsAbbuchungskontoErforderlichReturnsFalse(): void
    {
        $this->assertFalse($this->tanMode->getSmsAbbuchungskontoErforderlich());
    }

    public function testGetAuftraggeberkontoErforderlichReturnsFalse(): void
    {
        $this->assertFalse($this->tanMode->getAuftraggeberkontoErforderlich());
    }

    public function testGetChallengeKlasseErforderlichReturnsFalse(): void
    {
        $this->assertFalse($this->tanMode->getChallengeKlasseErforderlich());
    }

    public function testGetAntwortHhdUcErforderlichReturnsFalse(): void
    {
        $this->assertFalse($this->tanMode->getAntwortHhdUcErforderlich());
    }

    public function testGetMaxDecoupledChecksThrowsRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->tanMode->getMaxDecoupledChecks();
    }

    public function testGetFirstDecoupledCheckDelaySecondsThrowsRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->tanMode->getFirstDecoupledCheckDelaySeconds();
    }

    public function testGetPeriodicDecoupledCheckDelaySecondsThrowsRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->tanMode->getPeriodicDecoupledCheckDelaySeconds();
    }

    public function testAllowsManualConfirmationThrowsRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->tanMode->allowsManualConfirmation();
    }

    public function testAllowsAutomatedPollingThrowsRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->tanMode->allowsAutomatedPolling();
    }

    public function testCreateHKTANThrowsAssertionError(): void
    {
        $this->expectException(\AssertionError::class);
        $this->tanMode->createHKTAN();
    }

    public function testIdConstant(): void
    {
        $this->assertSame(-1, NoPsd2TanMode::ID);
    }
}

<?php

namespace BytesCommerce\Tests\Unit\Model;

use BytesCommerce\Model\NoPsd2TanMode;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for NoPsd2TanMode class.
 * Tests the placeholder TanMode implementation for banks without PSD2 support.
 */
class NoPsd2TanModeTest extends TestCase
{
    private NoPsd2TanMode $noPsd2TanMode;

    protected function setUp(): void
    {
        $this->noPsd2TanMode = new NoPsd2TanMode();
    }

    public function testGetIdReturnsNegativeOne(): void
    {
        $this->assertSame(-1, $this->noPsd2TanMode->getId());
    }

    public function testGetNameReturnsNoPsd2Message(): void
    {
        $this->assertSame('No PSD2/TANs supported', $this->noPsd2TanMode->getName());
    }

    public function testIsProzessvariante2ReturnsFalse(): void
    {
        $this->assertFalse($this->noPsd2TanMode->isProzessvariante2());
    }

    public function testIsDecoupledReturnsFalse(): void
    {
        $this->assertFalse($this->noPsd2TanMode->isDecoupled());
    }

    public function testGetChallengeLabelReturnsEmptyString(): void
    {
        $this->assertSame('', $this->noPsd2TanMode->getChallengeLabel());
    }

    public function testGetMaxChallengeLengthReturnsZero(): void
    {
        $this->assertSame(0, $this->noPsd2TanMode->getMaxChallengeLength());
    }

    public function testGetMaxTanLengthReturnsZero(): void
    {
        $this->assertSame(0, $this->noPsd2TanMode->getMaxTanLength());
    }

    public function testGetTanFormatReturnsZero(): void
    {
        $this->assertSame(0, $this->noPsd2TanMode->getTanFormat());
    }

    public function testNeedsTanMediumReturnsFalse(): void
    {
        $this->assertFalse($this->noPsd2TanMode->needsTanMedium());
    }

    public function testGetSmsAbbuchungskontoErforderlichReturnsFalse(): void
    {
        $this->assertFalse($this->noPsd2TanMode->getSmsAbbuchungskontoErforderlich());
    }

    public function testGetAuftraggeberkontoErforderlichReturnsFalse(): void
    {
        $this->assertFalse($this->noPsd2TanMode->getAuftraggeberkontoErforderlich());
    }

    public function testGetChallengeKlasseErforderlichReturnsFalse(): void
    {
        $this->assertFalse($this->noPsd2TanMode->getChallengeKlasseErforderlich());
    }

    public function testGetAntwortHhdUcErforderlichReturnsFalse(): void
    {
        $this->assertFalse($this->noPsd2TanMode->getAntwortHhdUcErforderlich());
    }

    public function testGetMaxDecoupledChecksThrowsRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->noPsd2TanMode->getMaxDecoupledChecks();
    }

    public function testGetFirstDecoupledCheckDelaySecondsThrowsRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->noPsd2TanMode->getFirstDecoupledCheckDelaySeconds();
    }

    public function testGetPeriodicDecoupledCheckDelaySecondsThrowsRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->noPsd2TanMode->getPeriodicDecoupledCheckDelaySeconds();
    }

    public function testAllowsManualConfirmationThrowsRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->noPsd2TanMode->allowsManualConfirmation();
    }

    public function testAllowsAutomatedPollingThrowsRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->noPsd2TanMode->allowsAutomatedPolling();
    }

    public function testCreateHKTANThrowsAssertionError(): void
    {
        $this->expectException(\AssertionError::class);
        $this->noPsd2TanMode->createHKTAN();
    }

    public function testIdConstant(): void
    {
        $this->assertSame(-1, NoPsd2TanMode::ID);
    }
}

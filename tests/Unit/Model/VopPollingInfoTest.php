<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Model;

use BytesCommerce\Model\VopPollingInfo;
use BytesCommerce\Syntax\Bin;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for VopPollingInfo class.
 * Tests the polling information for Verification of Payee long-running operations.
 */
class VopPollingInfoTest extends TestCase
{
    private string $aufsetzpunkt;

    private Bin $bin;

    private int $nextAttemptInSeconds;

    protected function setUp(): void
    {
        $this->aufsetzpunkt = 'test-aufsetzpunkt-value';
        $this->bin = new Bin('POLLING123');
        $this->nextAttemptInSeconds = 30;
    }

    public function testConstructorWithAllParameters(): void
    {
        $vopPollingInfo = new VopPollingInfo(
            $this->aufsetzpunkt,
            $this->bin,
            $this->nextAttemptInSeconds
        );

        $this->assertSame($this->aufsetzpunkt, $vopPollingInfo->getAufsetzpunkt());
        $this->assertSame($this->bin, $vopPollingInfo->getPollingId());
        $this->assertSame($this->nextAttemptInSeconds, $vopPollingInfo->getNextAttemptInSeconds());
    }

    public function testConstructorWithNullPollingId(): void
    {
        $vopPollingInfo = new VopPollingInfo(
            $this->aufsetzpunkt,
            null,
            $this->nextAttemptInSeconds
        );

        $this->assertSame($this->aufsetzpunkt, $vopPollingInfo->getAufsetzpunkt());
        $this->assertNull($vopPollingInfo->getPollingId());
        $this->assertSame($this->nextAttemptInSeconds, $vopPollingInfo->getNextAttemptInSeconds());
    }

    public function testConstructorWithNullNextAttemptInSeconds(): void
    {
        $vopPollingInfo = new VopPollingInfo(
            $this->aufsetzpunkt,
            $this->bin,
            null
        );

        $this->assertSame($this->aufsetzpunkt, $vopPollingInfo->getAufsetzpunkt());
        $this->assertSame($this->bin, $vopPollingInfo->getPollingId());
        $this->assertNull($vopPollingInfo->getNextAttemptInSeconds());
    }

    public function testConstructorWithAllNulls(): void
    {
        $vopPollingInfo = new VopPollingInfo(
            $this->aufsetzpunkt,
            null,
            null
        );

        $this->assertSame($this->aufsetzpunkt, $vopPollingInfo->getAufsetzpunkt());
        $this->assertNull($vopPollingInfo->getPollingId());
        $this->assertNull($vopPollingInfo->getNextAttemptInSeconds());
    }

    public function testGetAufsetzpunkt(): void
    {
        $aufsetzpunkt = 'unique-aufsetzpunkt-123';
        $vopPollingInfo = new VopPollingInfo($aufsetzpunkt, null, null);

        $this->assertSame($aufsetzpunkt, $vopPollingInfo->getAufsetzpunkt());
    }

    public function testGetPollingIdReturnsBinObject(): void
    {
        $bin = new Bin('POLLING_ID_ABC123');
        $vopPollingInfo = new VopPollingInfo($this->aufsetzpunkt, $bin, null);

        $result = $vopPollingInfo->getPollingId();
        $this->assertInstanceOf(Bin::class, $result);
        $this->assertSame($bin, $result);
    }

    public function testGetPollingIdReturnsNullWhenNotSet(): void
    {
        $vopPollingInfo = new VopPollingInfo($this->aufsetzpunkt, null, null);

        $this->assertNull($vopPollingInfo->getPollingId());
    }

    public function testGetNextAttemptInSecondsReturnsValue(): void
    {
        $vopPollingInfo = new VopPollingInfo($this->aufsetzpunkt, null, 60);

        $this->assertSame(60, $vopPollingInfo->getNextAttemptInSeconds());
    }

    public function testGetNextAttemptInSecondsReturnsNullWhenNotSet(): void
    {
        $vopPollingInfo = new VopPollingInfo($this->aufsetzpunkt, null, null);

        $this->assertNull($vopPollingInfo->getNextAttemptInSeconds());
    }

    public function testGetInformationForUserReturnsConstantString(): void
    {
        $vopPollingInfo = new VopPollingInfo($this->aufsetzpunkt, null, null);

        $expected = 'The bank is verifying payee information...';
        $this->assertSame($expected, $vopPollingInfo->getInformationForUser());
    }

    public function testGetInformationForUserIsConsistent(): void
    {
        $pollingInfo1 = new VopPollingInfo('aufsetzpunkt1', null, 30);
        $pollingInfo2 = new VopPollingInfo('aufsetzpunkt2', new Bin('id'), 60);

        $this->assertSame(
            $pollingInfo1->getInformationForUser(),
            $pollingInfo2->getInformationForUser()
        );
    }

    public function testMultiplePollingInfoInstancesAreIndependent(): void
    {
        $pollingInfo1 = new VopPollingInfo('aufsetzpunkt1', null, 30);
        $pollingInfo2 = new VopPollingInfo('aufsetzpunkt2', new Bin('different'), 60);

        $this->assertNotSame($pollingInfo1->getAufsetzpunkt(), $pollingInfo2->getAufsetzpunkt());
        $this->assertNotSame($pollingInfo1->getPollingId(), $pollingInfo2->getPollingId());
        $this->assertNotSame($pollingInfo1->getNextAttemptInSeconds(), $pollingInfo2->getNextAttemptInSeconds());
    }
}

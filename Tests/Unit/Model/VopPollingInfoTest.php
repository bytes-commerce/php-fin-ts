<?php

namespace Fhp\Tests\Unit\Model;

use Fhp\Model\VopPollingInfo;
use Fhp\Syntax\Bin;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for VopPollingInfo class.
 * Tests the polling information for Verification of Payee long-running operations.
 */
class VopPollingInfoTest extends TestCase
{
    private string $aufsetzpunkt;
    private Bin $pollingId;
    private int $nextAttemptInSeconds;

    protected function setUp(): void
    {
        $this->aufsetzpunkt = 'test-aufsetzpunkt-value';
        $this->pollingId = new Bin('POLLING123');
        $this->nextAttemptInSeconds = 30;
    }

    public function testConstructorWithAllParameters(): void
    {
        $pollingInfo = new VopPollingInfo(
            $this->aufsetzpunkt,
            $this->pollingId,
            $this->nextAttemptInSeconds
        );

        $this->assertSame($this->aufsetzpunkt, $pollingInfo->getAufsetzpunkt());
        $this->assertSame($this->pollingId, $pollingInfo->getPollingId());
        $this->assertSame($this->nextAttemptInSeconds, $pollingInfo->getNextAttemptInSeconds());
    }

    public function testConstructorWithNullPollingId(): void
    {
        $pollingInfo = new VopPollingInfo(
            $this->aufsetzpunkt,
            null,
            $this->nextAttemptInSeconds
        );

        $this->assertSame($this->aufsetzpunkt, $pollingInfo->getAufsetzpunkt());
        $this->assertNull($pollingInfo->getPollingId());
        $this->assertSame($this->nextAttemptInSeconds, $pollingInfo->getNextAttemptInSeconds());
    }

    public function testConstructorWithNullNextAttemptInSeconds(): void
    {
        $pollingInfo = new VopPollingInfo(
            $this->aufsetzpunkt,
            $this->pollingId,
            null
        );

        $this->assertSame($this->aufsetzpunkt, $pollingInfo->getAufsetzpunkt());
        $this->assertSame($this->pollingId, $pollingInfo->getPollingId());
        $this->assertNull($pollingInfo->getNextAttemptInSeconds());
    }

    public function testConstructorWithAllNulls(): void
    {
        $pollingInfo = new VopPollingInfo(
            $this->aufsetzpunkt,
            null,
            null
        );

        $this->assertSame($this->aufsetzpunkt, $pollingInfo->getAufsetzpunkt());
        $this->assertNull($pollingInfo->getPollingId());
        $this->assertNull($pollingInfo->getNextAttemptInSeconds());
    }

    public function testGetAufsetzpunkt(): void
    {
        $aufsetzpunkt = 'unique-aufsetzpunkt-123';
        $pollingInfo = new VopPollingInfo($aufsetzpunkt, null, null);

        $this->assertSame($aufsetzpunkt, $pollingInfo->getAufsetzpunkt());
    }

    public function testGetPollingIdReturnsBinObject(): void
    {
        $binData = new Bin('POLLING_ID_ABC123');
        $pollingInfo = new VopPollingInfo($this->aufsetzpunkt, $binData, null);

        $result = $pollingInfo->getPollingId();
        $this->assertInstanceOf(Bin::class, $result);
        $this->assertSame($binData, $result);
    }

    public function testGetPollingIdReturnsNullWhenNotSet(): void
    {
        $pollingInfo = new VopPollingInfo($this->aufsetzpunkt, null, null);

        $this->assertNull($pollingInfo->getPollingId());
    }

    public function testGetNextAttemptInSecondsReturnsValue(): void
    {
        $pollingInfo = new VopPollingInfo($this->aufsetzpunkt, null, 60);

        $this->assertSame(60, $pollingInfo->getNextAttemptInSeconds());
    }

    public function testGetNextAttemptInSecondsReturnsNullWhenNotSet(): void
    {
        $pollingInfo = new VopPollingInfo($this->aufsetzpunkt, null, null);

        $this->assertNull($pollingInfo->getNextAttemptInSeconds());
    }

    public function testGetInformationForUserReturnsConstantString(): void
    {
        $pollingInfo = new VopPollingInfo($this->aufsetzpunkt, null, null);

        $expected = 'The bank is verifying payee information...';
        $this->assertSame($expected, $pollingInfo->getInformationForUser());
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

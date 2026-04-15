<?php

namespace Fhp\Tests\Unit\Model;

use Fhp\Model\VopConfirmationRequestImpl;
use Fhp\Syntax\Bin;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for VopConfirmationRequestImpl class.
 * Tests the implementation of VopConfirmationRequest interface.
 */
class VopConfirmationRequestImplTest extends TestCase
{
    private Bin $vopId;
    private \DateTime $expiration;
    private VopConfirmationRequestImpl $request;

    protected function setUp(): void
    {
        $this->vopId = new Bin('1234567890ABCDEF');
        $this->expiration = new \DateTime('2025-12-31 23:59:59', new \DateTimeZone('UTC'));
    }

    public function testConstructorWithAllParameters(): void
    {
        $informationForUser = 'Please confirm';
        $verificationResult = 'CompletedFullMatch';
        $verificationNotApplicableReason = 'Not applicable reason';

        $this->request = new VopConfirmationRequestImpl(
            $this->vopId,
            $this->expiration,
            $informationForUser,
            $verificationResult,
            $verificationNotApplicableReason
        );

        $this->assertSame($this->vopId, $this->request->getVopId());
        $this->assertSame($this->expiration, $this->request->getExpiration());
        $this->assertSame($informationForUser, $this->request->getInformationForUser());
        $this->assertSame($verificationResult, $this->request->getVerificationResult());
        $this->assertSame($verificationNotApplicableReason, $this->request->getVerificationNotApplicableReason());
    }

    public function testConstructorWithNullExpiration(): void
    {
        $this->request = new VopConfirmationRequestImpl(
            $this->vopId,
            null,
            'Some info',
            'CompletedFullMatch',
            null
        );

        $this->assertSame($this->vopId, $this->request->getVopId());
        $this->assertNull($this->request->getExpiration());
        $this->assertSame('Some info', $this->request->getInformationForUser());
        $this->assertSame('CompletedFullMatch', $this->request->getVerificationResult());
        $this->assertNull($this->request->getVerificationNotApplicableReason());
    }

    public function testConstructorWithNullInformationForUser(): void
    {
        $this->request = new VopConfirmationRequestImpl(
            $this->vopId,
            $this->expiration,
            null,
            null,
            null
        );

        $this->assertSame($this->vopId, $this->request->getVopId());
        $this->assertSame($this->expiration, $this->request->getExpiration());
        $this->assertNull($this->request->getInformationForUser());
        $this->assertNull($this->request->getVerificationResult());
        $this->assertNull($this->request->getVerificationNotApplicableReason());
    }

    public function testGetVopId(): void
    {
        $this->request = new VopConfirmationRequestImpl(
            $this->vopId,
            $this->expiration,
            'info',
            'result',
            'reason'
        );

        $this->assertSame($this->vopId, $this->request->getVopId());
    }

    public function testGetExpiration(): void
    {
        $this->request = new VopConfirmationRequestImpl(
            $this->vopId,
            $this->expiration,
            'info',
            'result',
            'reason'
        );

        $this->assertSame($this->expiration, $this->request->getExpiration());
    }

    public function testGetInformationForUser(): void
    {
        $this->request = new VopConfirmationRequestImpl(
            $this->vopId,
            null,
            'Important information for user',
            null,
            null
        );

        $this->assertSame('Important information for user', $this->request->getInformationForUser());
    }

    public function testGetVerificationResult(): void
    {
        $this->request = new VopConfirmationRequestImpl(
            $this->vopId,
            null,
            null,
            'CompletedFullMatch',
            null
        );

        $this->assertSame('CompletedFullMatch', $this->request->getVerificationResult());
    }

    public function testGetVerificationNotApplicableReason(): void
    {
        $this->request = new VopConfirmationRequestImpl(
            $this->vopId,
            null,
            null,
            null,
            'Beneficiary account not found'
        );

        $this->assertSame('Beneficiary account not found', $this->request->getVerificationNotApplicableReason());
    }

    public function testAllNullParameters(): void
    {
        $this->request = new VopConfirmationRequestImpl(
            $this->vopId,
            null,
            null,
            null,
            null
        );

        $this->assertSame($this->vopId, $this->request->getVopId());
        $this->assertNull($this->request->getExpiration());
        $this->assertNull($this->request->getInformationForUser());
        $this->assertNull($this->request->getVerificationResult());
        $this->assertNull($this->request->getVerificationNotApplicableReason());
    }

    public function testGetVopIdReturnsBinObject(): void
    {
        $binData = new Bin('DEADBEEFCAFEBABE');
        $this->request = new VopConfirmationRequestImpl(
            $binData,
            null,
            null,
            null,
            null
        );

        $result = $this->request->getVopId();
        $this->assertInstanceOf(Bin::class, $result);
        $this->assertSame($binData, $result);
    }
}

<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Model;

use BytesCommerce\Model\VopConfirmationRequestImpl;
use BytesCommerce\Syntax\Bin;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for VopConfirmationRequestImpl class.
 * Tests the implementation of VopConfirmationRequest interface.
 */
class VopConfirmationRequestImplTest extends TestCase
{
    private Bin $bin;

    private \DateTime $expiration;

    private VopConfirmationRequestImpl $vopConfirmationRequestImpl;

    protected function setUp(): void
    {
        $this->bin = new Bin('1234567890ABCDEF');
        $this->expiration = new \DateTime('2025-12-31 23:59:59', new \DateTimeZone('UTC'));
    }

    public function testConstructorWithAllParameters(): void
    {
        $informationForUser = 'Please confirm';
        $verificationResult = 'CompletedFullMatch';
        $verificationNotApplicableReason = 'Not applicable reason';

        $this->vopConfirmationRequestImpl = new VopConfirmationRequestImpl(
            $this->bin,
            $this->expiration,
            $informationForUser,
            $verificationResult,
            $verificationNotApplicableReason
        );

        $this->assertSame($this->bin, $this->vopConfirmationRequestImpl->getVopId());
        $this->assertSame($this->expiration, $this->vopConfirmationRequestImpl->getExpiration());
        $this->assertSame($informationForUser, $this->vopConfirmationRequestImpl->getInformationForUser());
        $this->assertSame($verificationResult, $this->vopConfirmationRequestImpl->getVerificationResult());
        $this->assertSame($verificationNotApplicableReason, $this->vopConfirmationRequestImpl->getVerificationNotApplicableReason());
    }

    public function testConstructorWithNullExpiration(): void
    {
        $this->vopConfirmationRequestImpl = new VopConfirmationRequestImpl(
            $this->bin,
            null,
            'Some info',
            'CompletedFullMatch',
            null
        );

        $this->assertSame($this->bin, $this->vopConfirmationRequestImpl->getVopId());
        $this->assertNull($this->vopConfirmationRequestImpl->getExpiration());
        $this->assertSame('Some info', $this->vopConfirmationRequestImpl->getInformationForUser());
        $this->assertSame('CompletedFullMatch', $this->vopConfirmationRequestImpl->getVerificationResult());
        $this->assertNull($this->vopConfirmationRequestImpl->getVerificationNotApplicableReason());
    }

    public function testConstructorWithNullInformationForUser(): void
    {
        $this->vopConfirmationRequestImpl = new VopConfirmationRequestImpl(
            $this->bin,
            $this->expiration,
            null,
            null,
            null
        );

        $this->assertSame($this->bin, $this->vopConfirmationRequestImpl->getVopId());
        $this->assertSame($this->expiration, $this->vopConfirmationRequestImpl->getExpiration());
        $this->assertNull($this->vopConfirmationRequestImpl->getInformationForUser());
        $this->assertNull($this->vopConfirmationRequestImpl->getVerificationResult());
        $this->assertNull($this->vopConfirmationRequestImpl->getVerificationNotApplicableReason());
    }

    public function testGetVopId(): void
    {
        $this->vopConfirmationRequestImpl = new VopConfirmationRequestImpl(
            $this->bin,
            $this->expiration,
            'info',
            'result',
            'reason'
        );

        $this->assertSame($this->bin, $this->vopConfirmationRequestImpl->getVopId());
    }

    public function testGetExpiration(): void
    {
        $this->vopConfirmationRequestImpl = new VopConfirmationRequestImpl(
            $this->bin,
            $this->expiration,
            'info',
            'result',
            'reason'
        );

        $this->assertSame($this->expiration, $this->vopConfirmationRequestImpl->getExpiration());
    }

    public function testGetInformationForUser(): void
    {
        $this->vopConfirmationRequestImpl = new VopConfirmationRequestImpl(
            $this->bin,
            null,
            'Important information for user',
            null,
            null
        );

        $this->assertSame('Important information for user', $this->vopConfirmationRequestImpl->getInformationForUser());
    }

    public function testGetVerificationResult(): void
    {
        $this->vopConfirmationRequestImpl = new VopConfirmationRequestImpl(
            $this->bin,
            null,
            null,
            'CompletedFullMatch',
            null
        );

        $this->assertSame('CompletedFullMatch', $this->vopConfirmationRequestImpl->getVerificationResult());
    }

    public function testGetVerificationNotApplicableReason(): void
    {
        $this->vopConfirmationRequestImpl = new VopConfirmationRequestImpl(
            $this->bin,
            null,
            null,
            null,
            'Beneficiary account not found'
        );

        $this->assertSame('Beneficiary account not found', $this->vopConfirmationRequestImpl->getVerificationNotApplicableReason());
    }

    public function testAllNullParameters(): void
    {
        $this->vopConfirmationRequestImpl = new VopConfirmationRequestImpl(
            $this->bin,
            null,
            null,
            null,
            null
        );

        $this->assertSame($this->bin, $this->vopConfirmationRequestImpl->getVopId());
        $this->assertNull($this->vopConfirmationRequestImpl->getExpiration());
        $this->assertNull($this->vopConfirmationRequestImpl->getInformationForUser());
        $this->assertNull($this->vopConfirmationRequestImpl->getVerificationResult());
        $this->assertNull($this->vopConfirmationRequestImpl->getVerificationNotApplicableReason());
    }

    public function testGetVopIdReturnsBinObject(): void
    {
        $bin = new Bin('DEADBEEFCAFEBABE');
        $this->vopConfirmationRequestImpl = new VopConfirmationRequestImpl(
            $bin,
            null,
            null,
            null,
            null
        );

        $result = $this->vopConfirmationRequestImpl->getVopId();
        $this->assertInstanceOf(Bin::class, $result);
        $this->assertSame($bin, $result);
    }
}

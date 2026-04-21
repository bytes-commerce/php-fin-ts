<?php

declare(strict_types=1);

namespace BytesCommerce\Model;

use BytesCommerce\Syntax\Bin;

/** Application code should not interact directly with this type, see {@link VopConfirmationRequest instead}. */
class VopConfirmationRequestImpl implements VopConfirmationRequest
{
    public function __construct(private Bin $bin, private ?\DateTime $expiration, private ?string $informationForUser, private ?string $verificationResult, private ?string $verificationNotApplicableReason)
    {
    }

    public function getVopId(): Bin
    {
        return $this->bin;
    }

    public function getExpiration(): ?\DateTime
    {
        return $this->expiration;
    }

    public function getInformationForUser(): ?string
    {
        return $this->informationForUser;
    }

    public function getVerificationResult(): ?string
    {
        return $this->verificationResult;
    }

    public function getVerificationNotApplicableReason(): ?string
    {
        return $this->verificationNotApplicableReason;
    }
}

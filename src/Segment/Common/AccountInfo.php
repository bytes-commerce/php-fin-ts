<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\Common;

/**
 * Common interface for DEGs that represent accounts.
 */
interface AccountInfo
{
    /** This is the IBAN, if available, or the plain account number otherwise. */
    public function getAccountNumber(): string;

    /** This is the BIC, if available, or the country-specific bank code otherwise. */
    public function getBankIdentifier(): ?string;
}

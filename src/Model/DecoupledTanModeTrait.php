<?php

declare(strict_types=1);

namespace BytesCommerce\Model;

/**
 * Trait for decoupled-specific TAN mode functionality.
 *
 * This trait provides safe default implementations for decoupled-related methods.
 * Implementations that support decoupled TAN modes should override these methods
 * with appropriate values.
 */
trait DecoupledTanModeTrait
{
    /**
     * For decoupled TAN modes only.
     * @return int The maximum number of times that {@link FinTs::checkDecoupledSubmission()} may be called for a given
     *     {@link TanRequest}. The bank may treat exceeding this number like a wrong TAN input. 0 means infinity.
     */
    public function getMaxDecoupledChecks(): int
    {
        return 0;
    }

    /**
     * For decoupled TAN modes only.
     * @return int The minimum number of seconds to wait beween receiving the {@link TanRequest} and the first call to
     *     {@link FinTs::checkDecoupledSubmission()}.
     */
    public function getFirstDecoupledCheckDelaySeconds(): int
    {
        return 0;
    }

    /**
     * For decoupled TAN modes only.
     * @return int The minimum number of seconds to wait beween subsequent calls to
     *     {@link FinTs::checkDecoupledSubmission()}.
     */
    public function getPeriodicDecoupledCheckDelaySeconds(): int
    {
        return 0;
    }

    /**
     * For decoupled TAN modes only.
     *
     * If this function returns true, the application, while waiting for the user to confirm on their secondary device,
     * may ask the user to indicate manually when they have done so (as opposed to relying solely on automated polling
     * if allowed by {@link TanMode::allowsAutomatedPolling()}, which may be only allowed at quite low frequencies
     * depending on the bank).
     *
     * @return bool Whether manual confirmations by the user are allowed.
     */
    public function allowsManualConfirmation(): bool
    {
        return false;
    }

    /**
     * For decoupled TAN modes only.
     *
     * If this function returns true, the application may poll the server periodically and automatically while waiting
     * for the user to confirm on their secondary device, subject to the delays below.
     * @return bool Whether automated polling is allowed.
     */
    public function allowsAutomatedPolling(): bool
    {
        return false;
    }
}
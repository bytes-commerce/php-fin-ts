<?php

declare(strict_types=1);

namespace BytesCommerce\Model;

use BytesCommerce\Syntax\Bin;

/**
 * Application code should not interact directly with this type, see {@link PollingInfo instead}.
 *
 * When we send a request to the bank that requires a Verification of Payee, this means that the bank server has to
 * contact another bank's server and compare payee names. Especially for larger requests (e.g. bulk transfers), this can
 * take some time. During this time, the server asks the client to poll regularly in order to find out when the process
 * is done. This class contains the state that the client needs to do this polling.
 */
class VopPollingInfo implements PollingInfo
{
    public function __construct(private string $aufsetzpunkt, private ?Bin $bin, private ?int $nextAttemptInSeconds)
    {
    }

    public function getAufsetzpunkt(): string
    {
        return $this->aufsetzpunkt;
    }

    public function getPollingId(): ?Bin
    {
        return $this->bin;
    }

    public function getNextAttemptInSeconds(): ?int
    {
        return $this->nextAttemptInSeconds;
    }

    public function getInformationForUser(): string
    {
        return 'The bank is verifying payee information...';
    }
}

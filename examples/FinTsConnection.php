<?php

declare(strict_types=1);

namespace Fhp\Examples;

use Fhp\Action\GetSEPAAccounts;
use Fhp\FinTs;
use Fhp\Model\SEPAAccount;
use Fhp\Options\Credentials;
use Fhp\Options\FinTsOptions;
use Psr\Log\LoggerInterface;

/**
 * Manages the FinTS connection lifecycle including initialization, login, and TAN handling.
 * This class provides a simplified interface for establishing and maintaining a FinTS connection.
 */
class FinTsConnection
{
    private FinTs $fints;
    private ?TanModeHelper $tanModeHelper = null;
    private ?AuthenticationHelper $authHelper = null;

    private FinTsOptions $options;
    private Credentials $credentials;

    public function __construct(FinTsOptions $options, Credentials $credentials, ?FinTs $fints = null)
    {
        $this->options = $options;
        $this->credentials = $credentials;
        $this->fints = $fints ?? FinTs::new($options, $credentials);
    }

    /**
     * Create a new connection with basic options.
     */
    public static function create(string $url, string $bankCode, string $username, string $pin, ?string $productName = null, ?string $productVersion = null, ?LoggerInterface $logger = null): self
    {
        $options = new FinTsOptions();
        $options->url = $url;
        $options->bankCode = $bankCode;
        $options->productName = $productName ?? '';
        $options->productVersion = $productVersion ?? '1.0';

        $credentials = Credentials::create($username, $pin);

        $fints = FinTs::new($options, $credentials);
        if ($logger !== null) {
            $fints->setLogger($logger);
        }

        return new self($options, $credentials, $fints);
    }

    /**
     * Connect to the bank and perform login.
     * Returns an array with the login action which may require TAN authentication.
     *
     * @return array{0: GetSEPAAccounts, 1: SEPAAccount[]} Array containing accounts action and retrieved accounts
     * @throws \Fhp\CurlException
     * @throws \Fhp\Protocol\UnexpectedResponseException
     * @throws \Fhp\Protocol\ServerException
     */
    public function connect(): array
    {
        $login = $this->fints->login();

        if ($login->needsTan()) {
            $this->getAuthHelper()->handleStrongAuthentication($login);
        }

        return $this->fetchAccounts();
    }

    /**
     * Fetch all SEPA accounts.
     *
     * @return array{0: GetSEPAAccounts, 1: SEPAAccount[]}
     * @throws \Fhp\CurlException
     * @throws \Fhp\Protocol\UnexpectedResponseException
     * @throws \Fhp\Protocol\ServerException
     */
    public function fetchAccounts(): array
    {
        $getSepaAccounts = GetSEPAAccounts::create();
        $this->fints->execute($getSepaAccounts);

        if ($getSepaAccounts->needsTan()) {
            $this->getAuthHelper()->handleStrongAuthentication($getSepaAccounts);
        }

        return [$getSepaAccounts, $getSepaAccounts->getAccounts()];
    }

    /**
     * Get the underlying FinTs instance.
     */
    public function getFinTs(): FinTs
    {
        return $this->fints;
    }

    /**
     * Get all accounts that have been fetched.
     *
     * @return SEPAAccount[]
     * @throws \Fhp\CurlException
     * @throws \Fhp\Protocol\UnexpectedResponseException
     * @throws \Fhp\Protocol\ServerException
     */
    public function getAccounts(): array
    {
        return $this->fetchAccounts()[1];
    }

    /**
     * Get the TAN mode helper.
     */
    public function getTanModeHelper(): TanModeHelper
    {
        if ($this->tanModeHelper === null) {
            $this->tanModeHelper = new TanModeHelper($this->fints);
        }
        return $this->tanModeHelper;
    }

    /**
     * Get the authentication helper.
     */
    public function getAuthHelper(): AuthenticationHelper
    {
        if ($this->authHelper === null) {
            $this->authHelper = new AuthenticationHelper($this->fints, $this->getTanModeHelper());
        }
        return $this->authHelper;
    }

    /**
     * Close the FinTS connection.
     */
    public function close(): void
    {
        $this->fints->close();
    }

    /**
     * Get the FinTsOptions used for this connection.
     */
    public function getOptions(): FinTsOptions
    {
        return $this->options;
    }

    /**
     * Get the Credentials used for this connection.
     */
    public function getCredentials(): Credentials
    {
        return $this->credentials;
    }
}
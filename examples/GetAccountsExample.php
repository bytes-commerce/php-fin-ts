<?php

declare(strict_types=1);

namespace Fhp\Examples;

use Fhp\Action\GetSEPAAccounts;

/**
 * Example: Get SEPA accounts.
 * Demonstrates how to fetch and display available SEPA accounts using phpFinTS.
 */
class GetAccountsExample
{
    private FinTsConnection $connection;

    public function __construct(FinTsConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get all available SEPA accounts.
     *
     * @return \Fhp\Model\SEPAAccount[]
     * @throws \Fhp\CurlException
     * @throws \Fhp\Protocol\UnexpectedResponseException
     * @throws \Fhp\Protocol\ServerException
     */
    public function getAccounts(): array
    {
        $fints = $this->connection->getFinTs();

        $getSepaAccounts = GetSEPAAccounts::create();
        $fints->execute($getSepaAccounts);

        if ($getSepaAccounts->needsTan()) {
            $this->connection->getAuthHelper()->handleStrongAuthentication($getSepaAccounts);
        }

        return $getSepaAccounts->getAccounts();
    }

    /**
     * Display all accounts in a human-readable format.
     */
    public function displayAccounts(): void
    {
        $accounts = $this->getAccounts();
        print_r($accounts);
    }

    /**
     * Run this example.
     */
    public function run(): void
    {
        $this->displayAccounts();
    }
}
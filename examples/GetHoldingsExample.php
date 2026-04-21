<?php

declare(strict_types=1);

namespace Fhp\Examples;

use Fhp\Action\GetDepotAufstellung;
use Fhp\Action\GetSEPAAccounts;
use Fhp\Model\SEPAAccount;

/**
 * Example: Get depot/statement of holdings.
 * Demonstrates how to retrieve investment account holdings using phpFinTS.
 */
class GetHoldingsExample
{
    private FinTsConnection $connection;

    public function __construct(FinTsConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get holdings for a specific account.
     *
     * @param SEPAAccount $account The account to get holdings for
     * @return \Fhp\Model\StatementOfHoldings\StatementOfHoldings
     * @throws \Fhp\CurlException
     * @throws \Fhp\Protocol\UnexpectedResponseException
     * @throws \Fhp\Protocol\ServerException
     */
    public function getHoldings(SEPAAccount $account): \Fhp\Model\StatementOfHoldings\StatementOfHoldings
    {
        $fints = $this->connection->getFinTs();

        $getStatement = GetDepotAufstellung::create($account);
        $fints->execute($getStatement);

        if ($getStatement->needsTan()) {
            $this->connection->getAuthHelper()->handleStrongAuthentication($getStatement);
        }

        return $getStatement->getStatement();
    }

    /**
     * Get holdings for the first available account.
     *
     * @return \Fhp\Model\StatementOfHoldings\StatementOfHoldings
     * @throws \Fhp\CurlException
     * @throws \Fhp\Protocol\UnexpectedResponseException
     * @throws \Fhp\Protocol\ServerException
     */
    public function getHoldingsForFirstAccount(): \Fhp\Model\StatementOfHoldings\StatementOfHoldings
    {
        $accounts = $this->getAccounts();
        if (empty($accounts)) {
            throw new \RuntimeException('No accounts available');
        }
        return $this->getHoldings($accounts[0]);
    }

    /**
     * Get all accounts from the connection.
     *
     * @return SEPAAccount[]
     * @throws \Fhp\CurlException
     * @throws \Fhp\Protocol\UnexpectedResponseException
     * @throws \Fhp\Protocol\ServerException
     */
    private function getAccounts(): array
    {
        return $this->connection->fetchAccounts()[1];
    }

    /**
     * Display holdings in a human-readable format.
     */
    public function displayHoldings(\Fhp\Model\StatementOfHoldings\StatementOfHoldings $soa): void
    {
        foreach ($soa->getHoldings() as $holding) {
            echo '=======================================' . PHP_EOL;
            echo 'Name        : ' . $holding->getName() . PHP_EOL;
            echo 'Amount      : ' . $holding->getAmount() . PHP_EOL;
            echo 'Price       : ' . $holding->getPrice() . ' ' . $holding->getCurrency() . PHP_EOL;
            echo 'WKN         : ' . $holding->getWKN() . PHP_EOL;
            echo 'ISIN        : ' . $holding->getISIN() . PHP_EOL;
            echo 'B-Datum     : ' . $holding->getDate()->format('Y-m-d') . PHP_EOL;
            echo '=======================================' . PHP_EOL . PHP_EOL;
        }
        echo 'Found ' . count($soa->getHoldings()) . ' holdings.' . PHP_EOL;
    }

    /**
     * Run this example.
     */
    public function run(): void
    {
        $soa = $this->getHoldingsForFirstAccount();
        $this->displayHoldings($soa);
    }
}
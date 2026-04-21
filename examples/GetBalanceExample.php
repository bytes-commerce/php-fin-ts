<?php

declare(strict_types=1);

namespace Fhp\Examples;

use Fhp\Action\GetBalance;
use Fhp\Action\GetSEPAAccounts;
use Fhp\Model\SEPAAccount;

/**
 * Example: Get account balances.
 * Demonstrates how to fetch and display account balances using phpFinTS.
 */
class GetBalanceExample
{
    private FinTsConnection $connection;

    public function __construct(FinTsConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get balances for all accounts.
     *
     * @return GetBalance[]
     * @throws \Fhp\CurlException
     * @throws \Fhp\Protocol\UnexpectedResponseException
     * @throws \Fhp\Protocol\ServerException
     */
    public function getAllBalances(): array
    {
        $fints = $this->connection->getFinTs();
        $accounts = $this->connection->getAccounts();

        $balances = [];
        foreach ($accounts as $account) {
            $balances[] = $this->getBalanceForAccount($account);
        }

        return $balances;
    }

    /**
     * Get balance for a specific account.
     */
    public function getBalanceForAccount(SEPAAccount $account, bool $allAccounts = true): GetBalance
    {
        $fints = $this->connection->getFinTs();

        $getBalance = GetBalance::create($account, $allAccounts);
        $fints->execute($getBalance);

        if ($getBalance->needsTan()) {
            $this->connection->getAuthHelper()->handleStrongAuthentication($getBalance);
        }

        return $getBalance;
    }

    /**
     * Display all balances in a human-readable format.
     */
    public function displayBalances(): void
    {
        $balances = $this->getAllBalances();

        foreach ($balances as $getBalance) {
            foreach ($getBalance->getBalances() as $hisal) {
                $accountInfo = $hisal->getAccountInfo();
                $accNo = $accountInfo->getAccountNumber();

                $kontoprodukt = $hisal->getKontoproduktbezeichnung();
                if ($kontoprodukt !== null) {
                    $accNo .= ' (' . $kontoprodukt . ')';
                }

                $saldo = $hisal->getGebuchterSaldo();
                $amnt = $saldo->getAmount();
                $curr = $saldo->getCurrency();
                $date = $saldo->getTimestamp()->format('Y-m-d');

                echo "On $accNo you have $amnt $curr as of $date.\n";
            }
        }
    }

    /**
     * Run this example.
     */
    public function run(): void
    {
        $this->displayBalances();
    }
}
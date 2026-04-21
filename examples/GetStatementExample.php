<?php

declare(strict_types=1);

namespace Fhp\Examples;

use DateTime;
use Fhp\Action\GetSEPAAccounts;
use Fhp\Action\GetStatementOfAccount;
use Fhp\Model\SEPAAccount;
use Fhp\Model\StatementOfAccount\Statement;
use Fhp\Model\StatementOfAccount\Transaction;

/**
 * Example: Get statement of account.
 * Demonstrates how to retrieve and display account statements using phpFinTS.
 */
class GetStatementExample
{
    private FinTsConnection $connection;

    public function __construct(FinTsConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get statements for a specific account and date range.
     *
     * @param SEPAAccount $account The account to get statements for
     * @param DateTime $from Start date
     * @param DateTime $to End date
     * @param bool $allAccounts Whether to get statements for all accounts
     * @param bool $camtFormat Whether to prefer CAMT format (default true)
     * @return \Fhp\Model\StatementOfAccount\StatementOfAccount
     * @throws \Fhp\CurlException
     * @throws \Fhp\Protocol\UnexpectedResponseException
     * @throws \Fhp\Protocol\ServerException
     */
    public function getStatements(
        SEPAAccount $account,
        DateTime $from,
        DateTime $to,
        bool $allAccounts = false,
        bool $camtFormat = true
    ): \Fhp\Model\StatementOfAccount\StatementOfAccount {
        $fints = $this->connection->getFinTs();

        $getStatement = GetStatementOfAccount::create($account, $from, $to, $allAccounts, $camtFormat);
        $fints->execute($getStatement);

        if ($getStatement->needsTan()) {
            $this->connection->getAuthHelper()->handleStrongAuthentication($getStatement);
        }

        return $getStatement->getStatement();
    }

    /**
     * Get statements for the first available account.
     */
    public function getStatementsForFirstAccount(DateTime $from, DateTime $to): \Fhp\Model\StatementOfAccount\StatementOfAccount
    {
        $accounts = $this->getAccounts();
        if (empty($accounts)) {
            throw new \RuntimeException('No accounts available');
        }
        return $this->getStatements($accounts[0], $from, $to);
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
     * Display statements in a human-readable format.
     */
    public function displayStatements(\Fhp\Model\StatementOfAccount\StatementOfAccount $soa): void
    {
        foreach ($soa->getStatements() as $statement) {
            $this->displayStatement($statement);
        }
        echo 'Found ' . count($soa->getStatements()) . ' statements.' . PHP_EOL;
    }

    /**
     * Display a single statement.
     */
    private function displayStatement(Statement $statement): void
    {
        $creditDebit = $statement->getCreditDebit() === Statement::CD_DEBIT ? '-' : '';
        echo $statement->getDate()->format('Y-m-d') . ': Start Saldo: ' . $creditDebit . $statement->getStartBalance() . PHP_EOL;
        echo 'Transactions:' . PHP_EOL;
        echo '=======================================' . PHP_EOL;

        foreach ($statement->getTransactions() as $transaction) {
            $this->displayTransaction($transaction);
        }
    }

    /**
     * Display a single transaction.
     */
    private function displayTransaction(Transaction $transaction): void
    {
        $creditDebit = $transaction->getCreditDebit() === Transaction::CD_DEBIT ? '-' : '';
        echo "Booked      : " . ($transaction->getBooked() ? "true" : "false") . PHP_EOL;
        echo 'Amount      : ' . $creditDebit . $transaction->getAmount() . PHP_EOL;
        echo 'Booking text: ' . $transaction->getBookingText() . PHP_EOL;
        echo 'Name        : ' . $transaction->getName() . PHP_EOL;
        echo 'Description : ' . $transaction->getMainDescription() . PHP_EOL;
        echo 'EREF        : ' . $transaction->getEndToEndID() . PHP_EOL;
        echo '=======================================' . PHP_EOL . PHP_EOL;
    }

    /**
     * Run this example with default dates.
     */
    public function run(): void
    {
        $from = new DateTime('2022-07-15');
        $to = new DateTime();
        $soa = $this->getStatementsForFirstAccount($from, $to);
        $this->displayStatements($soa);
    }
}
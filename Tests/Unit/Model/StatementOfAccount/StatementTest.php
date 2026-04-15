<?php

namespace Fhp\Tests\Unit\Model\StatementOfAccount;

use Fhp\Model\StatementOfAccount\Statement;
use Fhp\Model\StatementOfAccount\Transaction;
use PHPUnit\Framework\TestCase;

class StatementTest extends TestCase
{
    private Statement $statement;

    protected function setUp(): void
    {
        $this->statement = new Statement();
    }

    public function testSetAndGetStartBalance(): void
    {
        $result = $this->statement->setStartBalance(1000.50);

        $this->assertSame(1000.50, $this->statement->getStartBalance());
        $this->assertSame($this->statement, $result);
    }

    public function testStartBalanceDefaultsToZero(): void
    {
        $this->assertSame(0.0, $this->statement->getStartBalance());
    }

    public function testSetAndGetEndBalance(): void
    {
        $result = $this->statement->setEndBalance(2000.75);

        $this->assertSame(2000.75, $this->statement->getEndBalance());
        $this->assertSame($this->statement, $result);
    }

    public function testEndBalanceDefaultsToNull(): void
    {
        $this->assertNull($this->statement->getEndBalance());
    }

    public function testSetAndGetCreditDebit(): void
    {
        $result = $this->statement->setCreditDebit(Statement::CD_CREDIT);

        $this->assertSame(Statement::CD_CREDIT, $this->statement->getCreditDebit());
        $this->assertSame($this->statement, $result);
    }

    public function testCreditDebitDefaultsToNull(): void
    {
        $this->assertNull($this->statement->getCreditDebit());
    }

    public function testSetAndGetCreditDebitWithNull(): void
    {
        $this->statement->setCreditDebit(null);
        $this->assertNull($this->statement->getCreditDebit());
    }

    public function testSetAndGetDate(): void
    {
        $date = new \DateTime('2024-01-15');
        $result = $this->statement->setDate($date);

        $this->assertSame($date, $this->statement->getDate());
        $this->assertSame($this->statement, $result);
    }

    public function testGetTransactionsInitiallyEmpty(): void
    {
        $this->assertSame([], $this->statement->getTransactions());
    }

    public function testAddTransaction(): void
    {
        $transaction = new Transaction();
        $transaction->setAmount(100.00);

        $this->statement->addTransaction($transaction);

        $this->assertCount(1, $this->statement->getTransactions());
        $this->assertSame($transaction, $this->statement->getTransactions()[0]);
    }

    public function testAddMultipleTransactions(): void
    {
        $transaction1 = new Transaction();
        $transaction1->setAmount(100.00);

        $transaction2 = new Transaction();
        $transaction2->setAmount(200.00);

        $this->statement->addTransaction($transaction1);
        $this->statement->addTransaction($transaction2);

        $this->assertCount(2, $this->statement->getTransactions());
    }

    public function testConstants(): void
    {
        $this->assertSame('credit', Statement::CD_CREDIT);
        $this->assertSame('debit', Statement::CD_DEBIT);
    }
}

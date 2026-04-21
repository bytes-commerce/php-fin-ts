<?php

namespace BytesCommerce\Tests\Unit\Model\StatementOfAccount;

use BytesCommerce\Model\StatementOfAccount\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    private Transaction $transaction;

    protected function setUp(): void
    {
        $this->transaction = new Transaction();
    }

    public function testSetAndGetBookingDate(): void
    {
        $date = new \DateTime('2024-01-15');
        $transaction = $this->transaction->setBookingDate($date);

        $this->assertSame($date, $this->transaction->getBookingDate());
        $this->assertSame($this->transaction, $transaction);
    }

    public function testSetBookingDateWithNull(): void
    {
        $this->transaction->setBookingDate();
        $this->assertNull($this->transaction->getBookingDate());
    }

    public function testSetAndGetValutaDate(): void
    {
        $date = new \DateTime('2024-01-16');
        $transaction = $this->transaction->setValutaDate($date);

        $this->assertSame($date, $this->transaction->getValutaDate());
        $this->assertSame($this->transaction, $transaction);
    }

    public function testSetValutaDateWithNull(): void
    {
        $this->transaction->setValutaDate();
        $this->assertNull($this->transaction->getValutaDate());
    }

    public function testSetAndGetAmount(): void
    {
        $transaction = $this->transaction->setAmount(123.45);

        $this->assertSame(123.45, $this->transaction->getAmount());
        $this->assertSame($this->transaction, $transaction);
    }

    public function testSetAndGetCreditDebit(): void
    {
        $transaction = $this->transaction->setCreditDebit(Transaction::CD_CREDIT);

        $this->assertSame(Transaction::CD_CREDIT, $this->transaction->getCreditDebit());
        $this->assertSame($this->transaction, $transaction);
    }

    public function testSetAndGetIsStorno(): void
    {
        $transaction = $this->transaction->setIsStorno(true);

        $this->assertTrue($this->transaction->isStorno());
        $this->assertSame($this->transaction, $transaction);
    }

    public function testSetAndGetBookingCode(): void
    {
        $transaction = $this->transaction->setBookingCode('ABC123');

        $this->assertSame('ABC123', $this->transaction->getBookingCode());
        $this->assertSame($this->transaction, $transaction);
    }

    public function testSetAndGetBookingText(): void
    {
        $transaction = $this->transaction->setBookingText('Payment');

        $this->assertSame('Payment', $this->transaction->getBookingText());
        $this->assertSame($this->transaction, $transaction);
    }

    public function testSetAndGetDescription1(): void
    {
        $transaction = $this->transaction->setDescription1('Description 1');

        $this->assertSame('Description 1', $this->transaction->getDescription1());
        $this->assertSame($this->transaction, $transaction);
    }

    public function testSetAndGetDescription2(): void
    {
        $transaction = $this->transaction->setDescription2('Description 2');

        $this->assertSame('Description 2', $this->transaction->getDescription2());
        $this->assertSame($this->transaction, $transaction);
    }

    public function testSetAndGetStructuredDescription(): void
    {
        $descriptions = ['SVWZ' => 'Test description', 'EREF' => 'EREF123'];
        $transaction = $this->transaction->setStructuredDescription($descriptions);

        $this->assertSame($descriptions, $this->transaction->getStructuredDescription());
        $this->assertSame($this->transaction, $transaction);
    }

    public function testGetMainDescription(): void
    {
        $this->transaction->setStructuredDescription(['SVWZ' => 'Main description']);
        $this->assertSame('Main description', $this->transaction->getMainDescription());
    }

    public function testGetMainDescriptionWhenNotSet(): void
    {
        $this->transaction->setStructuredDescription([]);
        $this->assertSame('', $this->transaction->getMainDescription());
    }

    public function testGetEndToEndID(): void
    {
        $this->transaction->setStructuredDescription(['EREF' => 'E2E-12345']);
        $this->assertSame('E2E-12345', $this->transaction->getEndToEndID());
    }

    public function testGetEndToEndIDWhenNotSet(): void
    {
        $this->transaction->setStructuredDescription([]);
        $this->assertSame('', $this->transaction->getEndToEndID());
    }

    public function testSetAndGetBankCode(): void
    {
        $transaction = $this->transaction->setBankCode('70000000');

        $this->assertSame('70000000', $this->transaction->getBankCode());
        $this->assertSame($this->transaction, $transaction);
    }

    public function testSetAndGetAccountNumber(): void
    {
        $transaction = $this->transaction->setAccountNumber('1234567890');

        $this->assertSame('1234567890', $this->transaction->getAccountNumber());
        $this->assertSame($this->transaction, $transaction);
    }

    public function testSetAndGetName(): void
    {
        $transaction = $this->transaction->setName('John Doe');

        $this->assertSame('John Doe', $this->transaction->getName());
        $this->assertSame($this->transaction, $transaction);
    }

    public function testSetAndGetBooked(): void
    {
        $transaction = $this->transaction->setBooked(true);

        $this->assertTrue($this->transaction->getBooked());
        $this->assertSame($this->transaction, $transaction);
    }

    public function testSetAndGetPN(): void
    {
        $transaction = $this->transaction->setPN(123);

        $this->assertSame(123, $this->transaction->getPN());
        $this->assertSame($this->transaction, $transaction);
    }

    public function testSetPNWithString(): void
    {
        $this->transaction->setPN('456');
        $this->assertSame(456, $this->transaction->getPN());
    }

    public function testSetAndGetTextKeyAddition(): void
    {
        $transaction = $this->transaction->setTextKeyAddition(51);

        $this->assertSame(51, $this->transaction->getTextKeyAddition());
        $this->assertSame($this->transaction, $transaction);
    }

    public function testSetTextKeyAdditionWithString(): void
    {
        $this->transaction->setTextKeyAddition('52');
        $this->assertSame(52, $this->transaction->getTextKeyAddition());
    }

    public function testGetDateReturnsBookingDate(): void
    {
        $date = new \DateTime('2024-01-15');
        $this->transaction->setBookingDate($date);

        $this->assertSame($date, $this->transaction->getDate());
    }

    public function testConstants(): void
    {
        $this->assertSame('credit', Transaction::CD_CREDIT);
        $this->assertSame('debit', Transaction::CD_DEBIT);
    }
}

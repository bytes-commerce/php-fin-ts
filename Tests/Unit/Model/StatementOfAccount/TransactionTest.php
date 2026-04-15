<?php

namespace Fhp\Tests\Unit\Model\StatementOfAccount;

use Fhp\Model\StatementOfAccount\Transaction;
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
        $result = $this->transaction->setBookingDate($date);

        $this->assertSame($date, $this->transaction->getBookingDate());
        $this->assertSame($this->transaction, $result);
    }

    public function testSetBookingDateWithNull(): void
    {
        $this->transaction->setBookingDate(null);
        $this->assertNull($this->transaction->getBookingDate());
    }

    public function testSetAndGetValutaDate(): void
    {
        $date = new \DateTime('2024-01-16');
        $result = $this->transaction->setValutaDate($date);

        $this->assertSame($date, $this->transaction->getValutaDate());
        $this->assertSame($this->transaction, $result);
    }

    public function testSetValutaDateWithNull(): void
    {
        $this->transaction->setValutaDate(null);
        $this->assertNull($this->transaction->getValutaDate());
    }

    public function testSetAndGetAmount(): void
    {
        $result = $this->transaction->setAmount(123.45);

        $this->assertSame(123.45, $this->transaction->getAmount());
        $this->assertSame($this->transaction, $result);
    }

    public function testSetAndGetCreditDebit(): void
    {
        $result = $this->transaction->setCreditDebit(Transaction::CD_CREDIT);

        $this->assertSame(Transaction::CD_CREDIT, $this->transaction->getCreditDebit());
        $this->assertSame($this->transaction, $result);
    }

    public function testSetAndGetIsStorno(): void
    {
        $result = $this->transaction->setIsStorno(true);

        $this->assertTrue($this->transaction->isStorno());
        $this->assertSame($this->transaction, $result);
    }

    public function testSetAndGetBookingCode(): void
    {
        $result = $this->transaction->setBookingCode('ABC123');

        $this->assertSame('ABC123', $this->transaction->getBookingCode());
        $this->assertSame($this->transaction, $result);
    }

    public function testSetAndGetBookingText(): void
    {
        $result = $this->transaction->setBookingText('Payment');

        $this->assertSame('Payment', $this->transaction->getBookingText());
        $this->assertSame($this->transaction, $result);
    }

    public function testSetAndGetDescription1(): void
    {
        $result = $this->transaction->setDescription1('Description 1');

        $this->assertSame('Description 1', $this->transaction->getDescription1());
        $this->assertSame($this->transaction, $result);
    }

    public function testSetAndGetDescription2(): void
    {
        $result = $this->transaction->setDescription2('Description 2');

        $this->assertSame('Description 2', $this->transaction->getDescription2());
        $this->assertSame($this->transaction, $result);
    }

    public function testSetAndGetStructuredDescription(): void
    {
        $descriptions = ['SVWZ' => 'Test description', 'EREF' => 'EREF123'];
        $result = $this->transaction->setStructuredDescription($descriptions);

        $this->assertSame($descriptions, $this->transaction->getStructuredDescription());
        $this->assertSame($this->transaction, $result);
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
        $result = $this->transaction->setBankCode('70000000');

        $this->assertSame('70000000', $this->transaction->getBankCode());
        $this->assertSame($this->transaction, $result);
    }

    public function testSetAndGetAccountNumber(): void
    {
        $result = $this->transaction->setAccountNumber('1234567890');

        $this->assertSame('1234567890', $this->transaction->getAccountNumber());
        $this->assertSame($this->transaction, $result);
    }

    public function testSetAndGetName(): void
    {
        $result = $this->transaction->setName('John Doe');

        $this->assertSame('John Doe', $this->transaction->getName());
        $this->assertSame($this->transaction, $result);
    }

    public function testSetAndGetBooked(): void
    {
        $result = $this->transaction->setBooked(true);

        $this->assertTrue($this->transaction->getBooked());
        $this->assertSame($this->transaction, $result);
    }

    public function testSetAndGetPN(): void
    {
        $result = $this->transaction->setPN(123);

        $this->assertSame(123, $this->transaction->getPN());
        $this->assertSame($this->transaction, $result);
    }

    public function testSetPNWithString(): void
    {
        $this->transaction->setPN('456');
        $this->assertSame(456, $this->transaction->getPN());
    }

    public function testSetAndGetTextKeyAddition(): void
    {
        $result = $this->transaction->setTextKeyAddition(51);

        $this->assertSame(51, $this->transaction->getTextKeyAddition());
        $this->assertSame($this->transaction, $result);
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

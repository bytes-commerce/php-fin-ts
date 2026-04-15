<?php

namespace Fhp\Tests\Unit\Model;

use Fhp\Model\Account;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Account model class.
 * Tests all getter/setter pairs and the fluent interface.
 */
class AccountTest extends TestCase
{
    private Account $account;

    protected function setUp(): void
    {
        $this->account = new Account();
    }

    public function testGetIdInitiallyNull(): void
    {
        $this->assertNull($this->account->getId());
    }

    public function testSetIdReturnsThis(): void
    {
        $result = $this->account->setId('test-id');
        $this->assertSame($this->account, $result);
    }

    public function testSetIdAndGetId(): void
    {
        $this->account->setId('test-id');
        $this->assertSame('test-id', $this->account->getId());
    }

    public function testGetAccountNumberInitiallyNull(): void
    {
        $this->assertNull($this->account->getAccountNumber());
    }

    public function testSetAccountNumberReturnsThis(): void
    {
        $result = $this->account->setAccountNumber('1234567890');
        $this->assertSame($this->account, $result);
    }

    public function testSetAccountNumberAndGetAccountNumber(): void
    {
        $this->account->setAccountNumber('1234567890');
        $this->assertSame('1234567890', $this->account->getAccountNumber());
    }

    public function testGetBankCodeInitiallyNull(): void
    {
        $this->assertNull($this->account->getBankCode());
    }

    public function testSetBankCodeReturnsThis(): void
    {
        $result = $this->account->setBankCode('70000000');
        $this->assertSame($this->account, $result);
    }

    public function testSetBankCodeAndGetBankCode(): void
    {
        $this->account->setBankCode('70000000');
        $this->assertSame('70000000', $this->account->getBankCode());
    }

    public function testGetIbanInitiallyNull(): void
    {
        $this->assertNull($this->account->getIban());
    }

    public function testSetIbanReturnsThis(): void
    {
        $result = $this->account->setIban('DE89370400440532013000');
        $this->assertSame($this->account, $result);
    }

    public function testSetIbanAndGetIban(): void
    {
        $this->account->setIban('DE89370400440532013000');
        $this->assertSame('DE89370400440532013000', $this->account->getIban());
    }

    public function testGetCustomerIdInitiallyNull(): void
    {
        $this->assertNull($this->account->getCustomerId());
    }

    public function testSetCustomerIdReturnsThis(): void
    {
        $result = $this->account->setCustomerId('customer-123');
        $this->assertSame($this->account, $result);
    }

    public function testSetCustomerIdAndGetCustomerId(): void
    {
        $this->account->setCustomerId('customer-123');
        $this->assertSame('customer-123', $this->account->getCustomerId());
    }

    public function testGetCurrencyInitiallyNull(): void
    {
        $this->assertNull($this->account->getCurrency());
    }

    public function testSetCurrencyReturnsThis(): void
    {
        $result = $this->account->setCurrency('EUR');
        $this->assertSame($this->account, $result);
    }

    public function testSetCurrencyAndGetCurrency(): void
    {
        $this->account->setCurrency('EUR');
        $this->assertSame('EUR', $this->account->getCurrency());
    }

    public function testGetAccountOwnerNameInitiallyNull(): void
    {
        $this->assertNull($this->account->getAccountOwnerName());
    }

    public function testSetAccountOwnerNameReturnsThis(): void
    {
        $result = $this->account->setAccountOwnerName('Max Mustermann');
        $this->assertSame($this->account, $result);
    }

    public function testSetAccountOwnerNameAndGetAccountOwnerName(): void
    {
        $this->account->setAccountOwnerName('Max Mustermann');
        $this->assertSame('Max Mustermann', $this->account->getAccountOwnerName());
    }

    public function testGetAccountDescriptionInitiallyNull(): void
    {
        $this->assertNull($this->account->getAccountDescription());
    }

    public function testSetAccountDescriptionReturnsThis(): void
    {
        $result = $this->account->setAccountDescription('Girokonto');
        $this->assertSame($this->account, $result);
    }

    public function testSetAccountDescriptionAndGetAccountDescription(): void
    {
        $this->account->setAccountDescription('Girokonto');
        $this->assertSame('Girokonto', $this->account->getAccountDescription());
    }

    public function testFluentInterface(): void
    {
        $result = $this->account
            ->setId('test-id')
            ->setAccountNumber('1234567890')
            ->setBankCode('70000000')
            ->setIban('DE89370400440532013000')
            ->setCustomerId('customer-123')
            ->setCurrency('EUR')
            ->setAccountOwnerName('Max Mustermann')
            ->setAccountDescription('Girokonto');

        $this->assertSame($this->account, $result);
        $this->assertSame('test-id', $this->account->getId());
        $this->assertSame('1234567890', $this->account->getAccountNumber());
        $this->assertSame('70000000', $this->account->getBankCode());
        $this->assertSame('DE89370400440532013000', $this->account->getIban());
        $this->assertSame('customer-123', $this->account->getCustomerId());
        $this->assertSame('EUR', $this->account->getCurrency());
        $this->assertSame('Max Mustermann', $this->account->getAccountOwnerName());
        $this->assertSame('Girokonto', $this->account->getAccountDescription());
    }

    public function testSetIdWithNullClearsValue(): void
    {
        $this->account->setId('test-id');
        $this->assertSame('test-id', $this->account->getId());

        $this->account->setId(null);
        $this->assertNull($this->account->getId());
    }

    public function testSetAllFieldsToNull(): void
    {
        $this->account
            ->setId('test-id')
            ->setAccountNumber('1234567890')
            ->setBankCode('70000000')
            ->setIban('DE89370400440532013000')
            ->setCustomerId('customer-123')
            ->setCurrency('EUR')
            ->setAccountOwnerName('Max Mustermann')
            ->setAccountDescription('Girokonto');

        $this->account->setId(null);
        $this->account->setAccountNumber(null);
        $this->account->setBankCode(null);
        $this->account->setIban(null);
        $this->account->setCustomerId(null);
        $this->account->setCurrency(null);
        $this->account->setAccountOwnerName(null);
        $this->account->setAccountDescription(null);

        $this->assertNull($this->account->getId());
        $this->assertNull($this->account->getAccountNumber());
        $this->assertNull($this->account->getBankCode());
        $this->assertNull($this->account->getIban());
        $this->assertNull($this->account->getCustomerId());
        $this->assertNull($this->account->getCurrency());
        $this->assertNull($this->account->getAccountOwnerName());
        $this->assertNull($this->account->getAccountDescription());
    }
}

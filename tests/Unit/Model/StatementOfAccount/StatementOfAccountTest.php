<?php

namespace BytesCommerce\Tests\Unit\Model\StatementOfAccount;

use BytesCommerce\Model\StatementOfAccount\Statement;
use BytesCommerce\Model\StatementOfAccount\StatementOfAccount;
use PHPUnit\Framework\TestCase;

class StatementOfAccountTest extends TestCase
{
    public function testGetStatementsInitiallyEmpty(): void
    {
        $statementOfAccount = new StatementOfAccount();
        $this->assertSame([], $statementOfAccount->getStatements());
    }

    public function testGetStatementForDateWhenNoStatements(): void
    {
        $statementOfAccount = new StatementOfAccount();
        $result = $statementOfAccount->getStatementForDate('2024-01-15');
        $this->assertNull($result);
    }

    public function testHasStatementForDateWhenNoStatements(): void
    {
        $statementOfAccount = new StatementOfAccount();
        $this->assertFalse($statementOfAccount->hasStatementForDate('2024-01-15'));
    }

    public function testGetStatementForDateWithDateTimeObject(): void
    {
        $statementOfAccount = new StatementOfAccount();
        $date = new \DateTime('2024-01-15');
        $result = $statementOfAccount->getStatementForDate($date);
        $this->assertNull($result);
    }

    public function testHasStatementForDateWithDateTimeObject(): void
    {
        $statementOfAccount = new StatementOfAccount();
        $date = new \DateTime('2024-01-15');
        $this->assertFalse($statementOfAccount->hasStatementForDate($date));
    }

    public function testGetStatementForDateWithInvalidDateString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid date');

        $statementOfAccount = new StatementOfAccount();
        $statementOfAccount->getStatementForDate('not-a-valid-date');
    }

    public function testFromMT940ArrayCreatesStatements(): void
    {
        $mt940Data = [
            '2024-01-15' => [
                'start_balance' => [
                    'amount' => '1000.00',
                    'credit_debit' => 'credit',
                ],
                'end_balance' => [
                    'amount' => '1100.00',
                    'credit_debit' => 'credit',
                ],
                'transactions' => [
                    [
                        'booking_date' => '2024-01-15',
                        'valuta_date' => '2024-01-15',
                        'credit_debit' => 'credit',
                        'is_storno' => false,
                        'amount' => 100.00,
                        'description' => [
                            'booking_code' => 'EC',
                            'booking_text' => 'Payment',
                            'description_1' => 'Desc 1',
                            'description_2' => 'Desc 2',
                            'description' => ['SVWZ' => 'Test'],
                            'name' => 'John Doe',
                            'bank_code' => '70000000',
                            'account_number' => '1234567890',
                            'primanoten_nr' => '12345',
                            'text_key_addition' => 51,
                        ],
                        'booked' => true,
                    ],
                ],
            ],
        ];

        $statementOfAccount = StatementOfAccount::fromMT940Array($mt940Data);

        $this->assertCount(1, $statementOfAccount->getStatements());
        $statements = $statementOfAccount->getStatements();
        $this->assertSame(1000.00, $statements[0]->getStartBalance());
        $this->assertSame(1100.00, $statements[0]->getEndBalance());
        $this->assertSame(Statement::CD_CREDIT, $statements[0]->getCreditDebit());
        $this->assertCount(1, $statements[0]->getTransactions());
    }

    public function testFromMT940ArrayWithMultipleTransactionsSameDate(): void
    {
        $mt940Data = [
            '2024-01-15' => [
                'start_balance' => [
                    'amount' => '1000.00',
                    'credit_debit' => 'credit',
                ],
                'end_balance' => [
                    'amount' => '1200.00',
                    'credit_debit' => 'credit',
                ],
                'transactions' => [
                    [
                        'booking_date' => '2024-01-15',
                        'valuta_date' => '2024-01-15',
                        'credit_debit' => 'credit',
                        'is_storno' => false,
                        'amount' => 100.00,
                        'description' => [
                            'booking_code' => 'EC',
                            'booking_text' => 'Payment 1',
                            'description_1' => 'Desc 1',
                            'description_2' => 'Desc 2',
                            'description' => ['SVWZ' => 'Test 1'],
                            'name' => 'John Doe',
                            'bank_code' => '70000000',
                            'account_number' => '1234567890',
                            'primanoten_nr' => '12345',
                            'text_key_addition' => 51,
                        ],
                        'booked' => true,
                    ],
                    [
                        'booking_date' => '2024-01-15',
                        'valuta_date' => '2024-01-15',
                        'credit_debit' => 'credit',
                        'is_storno' => false,
                        'amount' => 100.00,
                        'description' => [
                            'booking_code' => 'EC',
                            'booking_text' => 'Payment 2',
                            'description_1' => 'Desc 3',
                            'description_2' => 'Desc 4',
                            'description' => ['SVWZ' => 'Test 2'],
                            'name' => 'Jane Doe',
                            'bank_code' => '70000001',
                            'account_number' => '9876543210',
                            'primanoten_nr' => '12346',
                            'text_key_addition' => 52,
                        ],
                        'booked' => true,
                    ],
                ],
            ],
        ];

        $statementOfAccount = StatementOfAccount::fromMT940Array($mt940Data);

        $this->assertCount(1, $statementOfAccount->getStatements());
        $this->assertCount(2, $statementOfAccount->getStatements()[0]->getTransactions());
    }

    public function testFromMT940ArrayWithDebitEndBalance(): void
    {
        $mt940Data = [
            '2024-01-15' => [
                'start_balance' => [
                    'amount' => '1000.00',
                    'credit_debit' => 'credit',
                ],
                'end_balance' => [
                    'amount' => '900.00',
                    'credit_debit' => 'debit',
                ],
                'transactions' => [],
            ],
        ];

        $statementOfAccount = StatementOfAccount::fromMT940Array($mt940Data);

        $statements = $statementOfAccount->getStatements();
        $this->assertSame(-900.00, $statements[0]->getEndBalance());
    }

    public function testFromMT940ArrayWithEmptyTransactions(): void
    {
        $mt940Data = [
            '2024-01-15' => [
                'start_balance' => [
                    'amount' => '1000.00',
                    'credit_debit' => 'credit',
                ],
                'transactions' => [],
            ],
        ];

        $statementOfAccount = StatementOfAccount::fromMT940Array($mt940Data);

        $this->assertCount(1, $statementOfAccount->getStatements());
        $this->assertCount(0, $statementOfAccount->getStatements()[0]->getTransactions());
    }

    public function testFromMT940ArrayWithDescriptionReplacements(): void
    {
        $mt940Data = [
            '2024-01-15' => [
                'start_balance' => [
                    'amount' => '1000.00',
                    'credit_debit' => 'credit',
                ],
                'transactions' => [
                    [
                        'booking_date' => '2024-01-15',
                        'valuta_date' => '2024-01-15',
                        'credit_debit' => 'credit',
                        'is_storno' => false,
                        'amount' => 100.00,
                        'description' => [
                            'booking_code' => 'EC',
                            'booking_text' => 'Payment@@',
                            'description_1' => 'Desc@@1',
                            'description_2' => 'Desc@@2',
                            'description' => [
                                'SVWZ' => 'Test@@Description',
                            ],
                            'name' => 'John@@Doe',
                            'bank_code' => '70000000',
                            'account_number' => '1234567890',
                            'primanoten_nr' => '12345',
                            'text_key_addition' => 51,
                        ],
                        'booked' => true,
                    ],
                ],
            ],
        ];

        $statementOfAccount = StatementOfAccount::fromMT940Array($mt940Data);

        $transactions = $statementOfAccount->getStatements()[0]->getTransactions();
        $this->assertSame('Payment', $transactions[0]->getBookingText());
        $this->assertSame('Desc1', $transactions[0]->getDescription1());
        $this->assertSame('Desc2', $transactions[0]->getDescription2());
        $this->assertSame('JohnDoe', $transactions[0]->getName());
    }

    public function testFromCAMTArrayDelegatesToFromMT940Array(): void
    {
        $camtData = [
            '2024-01-15' => [
                'start_balance' => [
                    'amount' => '1000.00',
                    'credit_debit' => 'credit',
                ],
                'transactions' => [],
            ],
        ];

        $statementOfAccount = StatementOfAccount::fromCAMTArray($camtData);

        $this->assertCount(1, $statementOfAccount->getStatements());
    }
}

<?php

declare(strict_types=1);



namespace BytesCommerce\Model\StatementOfAccount;

use BytesCommerce\MT940\MT940;

class StatementOfAccount
{
    /** @var Statement[] */
    protected array $statements = [];

    /**
     * Get statements
     *
     * @return Statement[]
     */
    public function getStatements(): array
    {
        return $this->statements;
    }

    /**
     * Gets statement for given date.
     *
     * @param string|\DateTime $date
     */
    public function getStatementForDate($date): ?Statement
    {
        if (is_string($date)) {
            $date = self::parseDate($date);
        }

        foreach ($this->statements as $statement) {
            if ($statement->getDate() == $date) {
                return $statement;
            }
        }

        return null;
    }

    /**
     * Checks if a statement with given date exists.
     *
     * @param string|\DateTime $date
     */
    public function hasStatementForDate($date): bool
    {
        return $this->getStatementForDate($date) instanceof \BytesCommerce\Model\StatementOfAccount\Statement;
    }

    private static function parseDate(string $date): \DateTime
    {
        try {
            return new \DateTime($date);
        } catch (\Exception $exception) {
            throw new \InvalidArgumentException('Invalid date: ' . $date, 0, $exception);
        }
    }

    /**
     * @param array $array A parsed MT940 dataset, as returned from {@link MT940::parse()}.
     * @return StatementOfAccount A new instance that contains the given data.
     */
    public static function fromMT940Array(array $array): StatementOfAccount
    {
        $statementOfAccount = new StatementOfAccount();
        foreach ($array as $date => $statement) {
            if ($statementOfAccount->hasStatementForDate($date)) {
                $statementModel = $statementOfAccount->getStatementForDate($date);
            } else {
                $statementModel = new Statement();
                $statementModel->setDate(self::parseDate($date));
                if (isset($statement['start_balance']['amount'])) {
                    $statementModel->setStartBalance((float) $statement['start_balance']['amount']);
                }

                if (isset($statement['end_balance'])) {
                    $statementModel->setEndBalance((float) $statement['end_balance']['amount'] * ($statement['end_balance']['credit_debit'] == MT940::CD_CREDIT ? 1 : -1));
                }

                if (isset($statement['start_balance']['credit_debit'])) {
                    $statementModel->setCreditDebit($statement['start_balance']['credit_debit']);
                }

                $statementOfAccount->statements[] = $statementModel;
            }

            if (isset($statement['transactions'])) {
                foreach ($statement['transactions'] as $trx) {
                    $replaceIn = [
                        'booking_text',
                        'description_1',
                        'description_2',
                        'description',
                        'name',
                    ];
                    foreach ($replaceIn as $k) {
                        if (isset($trx['description'][$k])) {
                            $trx['description'][$k] = str_replace('@@', '', $trx['description'][$k]);
                        }
                    }

                    $transaction = new Transaction();
                    $transaction->setBookingDate(self::parseDate($trx['booking_date']));
                    $transaction->setValutaDate(self::parseDate($trx['valuta_date']));
                    $transaction->setCreditDebit($trx['credit_debit']);
                    $transaction->setIsStorno($trx['is_storno']);
                    $transaction->setAmount($trx['amount']);
                    $transaction->setBookingCode($trx['description']['booking_code']);
                    $transaction->setBookingText($trx['description']['booking_text']);
                    $transaction->setDescription1($trx['description']['description_1']);
                    $transaction->setDescription2($trx['description']['description_2']);
                    $transaction->setStructuredDescription($trx['description']['description']);
                    $transaction->setBankCode($trx['description']['bank_code']);
                    $transaction->setAccountNumber($trx['description']['account_number']);
                    $transaction->setName($trx['description']['name']);
                    $transaction->setBooked($trx['booked']);
                    $transaction->setPN($trx['description']['primanoten_nr']);
                    $transaction->setTextKeyAddition($trx['description']['text_key_addition']);
                    $statementModel->addTransaction($transaction);
                }
            }
        }

        return $statementOfAccount;
    }

    /**
     * @param array $array A parsed CAMT dataset, as returned from {@link \BytesCommerce\CAMT\CAMT::parse()}.
     * @return StatementOfAccount A new instance that contains the given data.
     */
    public static function fromCAMTArray(array $array): StatementOfAccount
    {
        // CAMT data structure is compatible with MT940 structure, so we can reuse the same method
        return self::fromMT940Array($array);
    }
}

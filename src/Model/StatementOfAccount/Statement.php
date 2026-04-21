<?php

declare(strict_types=1);



namespace BytesCommerce\Model\StatementOfAccount;

class Statement
{
    public const CD_CREDIT = 'credit';

    public const CD_DEBIT = 'debit';

    /** @var Transaction[] */
    protected array $transactions = [];

    protected float $startBalance = 0.0;

    protected ?float $endBalance = null;

    protected ?string $creditDebit = null;

    protected ?\DateTime $date = null;

    /**
     * Get transactions
     *
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }

    /**
     * Get startBalance
     */
    public function getStartBalance(): float
    {
        return $this->startBalance;
    }

    /**
     * Set startBalance
     *
     * @return $this
     */
    public function setStartBalance(float $startBalance): static
    {
        $this->startBalance = $startBalance;

        return $this;
    }

    /**
     * Get endBalance
     * @return ?float returns the value, if given by the bank or null if unknown
     */
    public function getEndBalance(): ?float
    {
        return $this->endBalance;
    }

    /**
     * Set endBalance
     *
     * @return $this
     */
    public function setEndBalance(float $endBalance): static
    {
        $this->endBalance = $endBalance;

        return $this;
    }

    /**
     * Get creditDebit
     */
    public function getCreditDebit(): ?string
    {
        return $this->creditDebit;
    }

    /**
     * Set creditDebit
     *
     * @return $this
     */
    public function setCreditDebit(?string $creditDebit): static
    {
        $this->creditDebit = $creditDebit;

        return $this;
    }

    /**
     * Get date
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * Set date
     *
     * @return $this
     */
    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }
}

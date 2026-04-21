<?php

declare(strict_types=1);



namespace BytesCommerce\Model\StatementOfHoldings;

class StatementOfHoldings
{
    /**
     * @var Holding[]
     */
    protected $holdings = [];

    /**
     * Get statements
     *
     * @return Holding[]
     */
    public function getHoldings(): array
    {
        return $this->holdings;
    }

    public function addHolding(Holding $holding): void
    {
        $this->holdings[] = $holding;
    }
}

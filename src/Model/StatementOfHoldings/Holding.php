<?php

declare(strict_types=1);



namespace BytesCommerce\Model\StatementOfHoldings;

class Holding
{
    /**
     * @var string|null
     */
    protected $isin;

    /**
     * @var string|null
     */
    protected $wkn;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var float|null
     */
    protected $acquisitionPrice;

    /**
     * @var float|null
     */
    protected $price;

    /**
     * @var float|null
     */
    protected $amount;

    /**
     * @var float|null
     */
    protected $value;

    /**
     * @var \DateTime|null
     */
    protected $date;

    /**
     * @var \DateTime|null
     */
    protected $time;

    /**
     * @var string|null
     */
    protected $currency;

    /**
     * Set ISIN
     *
     * @return $this
     */
    public function setISIN(?string $isin): static
    {
        $this->isin = $isin;

        return $this;
    }

    /**
     * Set WKN
     *
     * @return $this
     */
    public function setWKN(?string $wkn): static
    {
        $this->wkn = $wkn;

        return $this;
    }

    /**
     * Set Name
     *
     * @return $this
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set value
     *
     * @return $this
     */
    public function setValue(?float $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Set price
     *
     * @return $this
     */
    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Set acquisition price
     *
     * @return $this
     */
    public function setAcquisitionPrice(?float $price): static
    {
        $this->acquisitionPrice = $price;

        return $this;
    }

    /**
     * Set amount
     *
     * @return $this
     */
    public function setAmount(?float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Set currency
     *
     * @return $this
     */
    public function setCurrency(?string $currency): static
    {
        $this->currency = $currency;

        return $this;
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

    /**
     * Set time
     *
     * @return $this
     */
    public function setTime(\DateTime $time): static
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get ISIN
     */
    public function getISIN(): ?string
    {
        return $this->isin;
    }

    /**
     * Get WKN
     */
    public function getWKN(): ?string
    {
        return $this->wkn;
    }

    /**
     * Get Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get value
     */
    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * Get acquisition price
     */
    public function getAcquisitionPrice(): ?float
    {
        return $this->acquisitionPrice;
    }

    /**
     * Get price
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * Get amount
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * Get currency
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * Get time
     */
    public function getTime(): ?\DateTime
    {
        return $this->time;
    }

    /**
     * Get date
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }
}

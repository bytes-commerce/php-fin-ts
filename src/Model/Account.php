<?php

declare(strict_types=1);

/** @noinspection PhpUnused */

namespace BytesCommerce\Model;

/**
 * Note: This account information is obtained from the HIUPD contained in the UPD data, but it lacks the BIC.
 */
class Account
{
    public function __construct(
        private ?string $id = null,
        private ?string $accountNumber = null,
        private ?string $bankCode = null,
        private ?string $iban = null,
        private ?string $customerId = null,
        private ?string $currency = null,
        private ?string $accountOwnerName = null,
        private ?string $accountDescription = null,
    ) {}

    public function getId(): ?string
    {
        return $this->id;
    }

    public function withId(?string $id): static
    {
        return new self($id, $this->accountNumber, $this->bankCode, $this->iban, $this->customerId, $this->currency, $this->accountOwnerName, $this->accountDescription);
    }

    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    public function withAccountNumber(?string $accountNumber): static
    {
        return new self($this->id, $accountNumber, $this->bankCode, $this->iban, $this->customerId, $this->currency, $this->accountOwnerName, $this->accountDescription);
    }

    public function getBankCode(): ?string
    {
        return $this->bankCode;
    }

    public function withBankCode(?string $bankCode): static
    {
        return new self($this->id, $this->accountNumber, $bankCode, $this->iban, $this->customerId, $this->currency, $this->accountOwnerName, $this->accountDescription);
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function withIban(?string $iban): static
    {
        return new self($this->id, $this->accountNumber, $this->bankCode, $iban, $this->customerId, $this->currency, $this->accountOwnerName, $this->accountDescription);
    }

    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function withCustomerId(?string $customerId): static
    {
        return new self($this->id, $this->accountNumber, $this->bankCode, $this->iban, $customerId, $this->currency, $this->accountOwnerName, $this->accountDescription);
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function withCurrency(?string $currency): static
    {
        return new self($this->id, $this->accountNumber, $this->bankCode, $this->iban, $this->customerId, $currency, $this->accountOwnerName, $this->accountDescription);
    }

    public function getAccountOwnerName(): ?string
    {
        return $this->accountOwnerName;
    }

    public function withAccountOwnerName(?string $accountOwnerName): static
    {
        return new self($this->id, $this->accountNumber, $this->bankCode, $this->iban, $this->customerId, $this->currency, $accountOwnerName, $this->accountDescription);
    }

    public function getAccountDescription(): ?string
    {
        return $this->accountDescription;
    }

    public function withAccountDescription(?string $accountDescription): static
    {
        return new self($this->id, $this->accountNumber, $this->bankCode, $this->iban, $this->customerId, $this->currency, $this->accountOwnerName, $accountDescription);
    }
}
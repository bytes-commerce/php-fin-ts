<?php

declare(strict_types=1);

/** @noinspection PhpUnused */

namespace BytesCommerce\Options;

/**
 * Holds options for FinTS connections and operations. These options are independent of the user and depend only on the
 * bank system and the client system that uses this library. This class mostly serves to pass the data around internally
 * within the library.
 */
class FinTsOptions
{
    public function __construct(
        public string $productName = '',
        public string $productVersion = '',
        public string $bankCode = '',
        public string $url = '',
        public int $timeoutConnect = 15,
        public int $timeoutResponse = 30,
    ) {}

    /**
     * @throws \InvalidArgumentException If the options are invalid.
     */
    public function validate(): void
    {
        $this->productName = trim($this->productName);
        $this->productVersion = trim($this->productVersion);
        $this->bankCode = trim($this->bankCode);
        $this->url = trim($this->url);

        if ($this->productName === '') {
            throw new \InvalidArgumentException('Product name required!');
        }

        if ($this->productVersion === '') {
            throw new \InvalidArgumentException('Product version required!');
        }

        if ($this->bankCode === '') {
            throw new \InvalidArgumentException('Bank code required!');
        }

        if ($this->url === '') {
            throw new \InvalidArgumentException('Server URL required!');
        }
    }
}
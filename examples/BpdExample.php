<?php

declare(strict_types=1);

namespace Fhp\Examples;

use Fhp\FinTs;
use Fhp\Options\FinTsOptions;

/**
 * Example: Fetch BPD (Bank Parameter Data).
 * Demonstrates how to retrieve BPD without user-specific credentials.
 *
 * This is useful for exploring the bank's FinTS features without having/risking own credentials.
 */
class BpdExample
{
    private FinTsOptions $options;

    public function __construct(FinTsOptions $options)
    {
        $this->options = $options;
    }

    /**
     * Create a BPD example with typical DKB parameters.
     */
    public static function createDkbExample(): self
    {
        $options = new FinTsOptions();
        $options->url = 'https://banking-dkb.s-fints-pt-dkb.de/fints30';
        $options->bankCode = '12030000';
        $options->productName = 'Dummy';
        $options->productVersion = '1.0';

        return new self($options);
    }

    /**
     * Fetch the BPD from the bank.
     *
     * @return \Fhp\Protocol\BPD The bank parameter data
     * @throws \Fhp\CurlException
     * @throws \Fhp\Protocol\UnexpectedResponseException
     * @throws \Fhp\Protocol\ServerException
     */
    public function fetchBpd(): \Fhp\Protocol\BPD
    {
        return FinTs::fetchBpd($this->options, new \Tests\Fhp\CLILogger());
    }

    /**
     * Run this example.
     */
    public function run(): void
    {
        $bpd = $this->fetchBpd();
        print_r($bpd);
    }
}
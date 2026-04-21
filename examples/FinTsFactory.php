<?php

declare(strict_types=1);

namespace Fhp\Examples;

use Fhp\FinTs;
use Fhp\Options\Credentials;
use Fhp\Options\FinTsOptions;
use Psr\Log\LoggerInterface;

/**
 * Factory for creating FinTs instances with proper configuration.
 * This replaces the procedural init.php approach with an object-oriented factory.
 */
class FinTsFactory
{
    /**
     * Create a new FinTs instance with the given options and credentials.
     */
    public static function create(FinTsOptions $options, Credentials $credentials, ?LoggerInterface $logger = null): FinTs
    {
        $fints = FinTs::new($options, $credentials);
        if ($logger !== null) {
            $fints->setLogger($logger);
        }
        return $fints;
    }

    /**
     * Create a FinTs instance from individual parameters.
     */
    public static function createFromParams(
        string $url,
        string $bankCode,
        string $productName,
        string $productVersion,
        string $username,
        string $pin,
        ?LoggerInterface $logger = null
    ): FinTs {
        $options = new FinTsOptions();
        $options->url = $url;
        $options->bankCode = $bankCode;
        $options->productName = $productName;
        $options->productVersion = $productVersion;

        $credentials = Credentials::create($username, $pin);

        return self::create($options, $credentials, $logger);
    }

    /**
     * Create a FinTsOptions instance with typical values for a German bank.
     */
    public static function createOptions(string $url, string $bankCode, string $productName, string $productVersion = '1.0'): FinTsOptions
    {
        $options = new FinTsOptions();
        $options->url = $url;
        $options->bankCode = $bankCode;
        $options->productName = $productName;
        $options->productVersion = $productVersion;
        return $options;
    }
}
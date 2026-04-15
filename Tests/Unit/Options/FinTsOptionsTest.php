<?php

namespace Fhp\Tests\Unit\Options;

use Fhp\Options\FinTsOptions;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for FinTsOptions class.
 * Tests configuration options validation.
 */
class FinTsOptionsTest extends TestCase
{
    private FinTsOptions $options;

    protected function setUp(): void
    {
        $this->options = new FinTsOptions();
    }

    public function testSetAndGetProductName(): void
    {
        $this->options->productName = 'TestProduct';
        $this->assertSame('TestProduct', $this->options->productName);
    }

    public function testSetAndGetProductVersion(): void
    {
        $this->options->productVersion = '1.0.0';
        $this->assertSame('1.0.0', $this->options->productVersion);
    }

    public function testSetAndGetBankCode(): void
    {
        $this->options->bankCode = '70000000';
        $this->assertSame('70000000', $this->options->bankCode);
    }

    public function testSetAndGetUrl(): void
    {
        $this->options->url = 'https://my-bank.de/fints';
        $this->assertSame('https://my-bank.de/fints', $this->options->url);
    }

    public function testDefaultTimeouts(): void
    {
        $options = new FinTsOptions();
        $this->assertSame(15, $options->timeoutConnect);
        $this->assertSame(30, $options->timeoutResponse);
    }

    public function testCustomTimeouts(): void
    {
        $options = new FinTsOptions();
        $options->timeoutConnect = 30;
        $options->timeoutResponse = 60;

        $this->assertSame(30, $options->timeoutConnect);
        $this->assertSame(60, $options->timeoutResponse);
    }

    public function testValidateWithAllValidOptions(): void
    {
        $this->options->productName = 'TestProduct';
        $this->options->productVersion = '1.0';
        $this->options->bankCode = '70000000';
        $this->options->url = 'https://testbank.de/fints';

        // Should not throw
        $this->options->validate();

        $this->assertSame('TestProduct', $this->options->productName);
        $this->assertSame('1.0', $this->options->productVersion);
    }

    public function testValidateTrimsWhitespace(): void
    {
        $this->options->productName = '  TestProduct  ';
        $this->options->productVersion = '  1.0  ';
        $this->options->bankCode = '  70000000  ';
        $this->options->url = '  https://testbank.de/fints  ';

        $this->options->validate();

        $this->assertSame('TestProduct', $this->options->productName);
        $this->assertSame('1.0', $this->options->productVersion);
        $this->assertSame('70000000', $this->options->bankCode);
        $this->assertSame('https://testbank.de/fints', $this->options->url);
    }

    public function testValidateThrowsExceptionForEmptyProductName(): void
    {
        $this->options->productName = '';
        $this->options->productVersion = '1.0';
        $this->options->bankCode = '70000000';
        $this->options->url = 'https://testbank.de/fints';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product name required!');

        $this->options->validate();
    }

    public function testValidateThrowsExceptionForWhitespaceOnlyProductName(): void
    {
        $this->options->productName = '   ';
        $this->options->productVersion = '1.0';
        $this->options->bankCode = '70000000';
        $this->options->url = 'https://testbank.de/fints';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product name required!');

        $this->options->validate();
    }

    public function testValidateThrowsExceptionForEmptyProductVersion(): void
    {
        $this->options->productName = 'TestProduct';
        $this->options->productVersion = '';
        $this->options->bankCode = '70000000';
        $this->options->url = 'https://testbank.de/fints';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product version required!');

        $this->options->validate();
    }

    public function testValidateThrowsExceptionForEmptyBankCode(): void
    {
        $this->options->productName = 'TestProduct';
        $this->options->productVersion = '1.0';
        $this->options->bankCode = '';
        $this->options->url = 'https://testbank.de/fints';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Bank code required!');

        $this->options->validate();
    }

    public function testValidateThrowsExceptionForEmptyUrl(): void
    {
        $this->options->productName = 'TestProduct';
        $this->options->productVersion = '1.0';
        $this->options->bankCode = '70000000';
        $this->options->url = '';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Server URL required!');

        $this->options->validate();
    }

    public function testValidateThrowsExceptionForWhitespaceOnlyUrl(): void
    {
        $this->options->productName = 'TestProduct';
        $this->options->productVersion = '1.0';
        $this->options->bankCode = '70000000';
        $this->options->url = '   ';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Server URL required!');

        $this->options->validate();
    }

    public function testValidateWithUrlIncludingPort(): void
    {
        $this->options->productName = 'TestProduct';
        $this->options->productVersion = '1.0';
        $this->options->bankCode = '70000000';
        $this->options->url = 'https://testbank.de:8443/fints';

        $this->options->validate();

        $this->assertSame('https://testbank.de:8443/fints', $this->options->url);
    }

    public function testValidateWithHttpUrl(): void
    {
        $this->options->productName = 'TestProduct';
        $this->options->productVersion = '1.0';
        $this->options->bankCode = '70000000';
        $this->options->url = 'http://testbank.de/fints';

        // HTTP should be allowed (library doesn't enforce HTTPS)
        $this->options->validate();

        $this->assertSame('http://testbank.de/fints', $this->options->url);
    }
}

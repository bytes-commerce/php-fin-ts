<?php

namespace BytesCommerce\Tests\Unit\Options;

use BytesCommerce\Options\FinTsOptions;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for FinTsOptions class.
 * Tests configuration options validation.
 */
class FinTsOptionsTest extends TestCase
{
    private FinTsOptions $finTsOptions;

    protected function setUp(): void
    {
        $this->finTsOptions = new FinTsOptions();
    }

    public function testSetAndGetProductName(): void
    {
        $this->finTsOptions->productName = 'TestProduct';
        $this->assertSame('TestProduct', $this->finTsOptions->productName);
    }

    public function testSetAndGetProductVersion(): void
    {
        $this->finTsOptions->productVersion = '1.0.0';
        $this->assertSame('1.0.0', $this->finTsOptions->productVersion);
    }

    public function testSetAndGetBankCode(): void
    {
        $this->finTsOptions->bankCode = '70000000';
        $this->assertSame('70000000', $this->finTsOptions->bankCode);
    }

    public function testSetAndGetUrl(): void
    {
        $this->finTsOptions->url = 'https://my-bank.de/fints';
        $this->assertSame('https://my-bank.de/fints', $this->finTsOptions->url);
    }

    public function testDefaultTimeouts(): void
    {
        $finTsOptions = new FinTsOptions();
        $this->assertSame(15, $finTsOptions->timeoutConnect);
        $this->assertSame(30, $finTsOptions->timeoutResponse);
    }

    public function testCustomTimeouts(): void
    {
        $finTsOptions = new FinTsOptions();
        $finTsOptions->timeoutConnect = 30;
        $finTsOptions->timeoutResponse = 60;

        $this->assertSame(30, $finTsOptions->timeoutConnect);
        $this->assertSame(60, $finTsOptions->timeoutResponse);
    }

    public function testValidateWithAllValidOptions(): void
    {
        $this->finTsOptions->productName = 'TestProduct';
        $this->finTsOptions->productVersion = '1.0';
        $this->finTsOptions->bankCode = '70000000';
        $this->finTsOptions->url = 'https://testbank.de/fints';

        // Should not throw
        $this->finTsOptions->validate();

        $this->assertSame('TestProduct', $this->finTsOptions->productName);
        $this->assertSame('1.0', $this->finTsOptions->productVersion);
    }

    public function testValidateTrimsWhitespace(): void
    {
        $this->finTsOptions->productName = '  TestProduct  ';
        $this->finTsOptions->productVersion = '  1.0  ';
        $this->finTsOptions->bankCode = '  70000000  ';
        $this->finTsOptions->url = '  https://testbank.de/fints  ';

        $this->finTsOptions->validate();

        $this->assertSame('TestProduct', $this->finTsOptions->productName);
        $this->assertSame('1.0', $this->finTsOptions->productVersion);
        $this->assertSame('70000000', $this->finTsOptions->bankCode);
        $this->assertSame('https://testbank.de/fints', $this->finTsOptions->url);
    }

    public function testValidateThrowsExceptionForEmptyProductName(): void
    {
        $this->finTsOptions->productName = '';
        $this->finTsOptions->productVersion = '1.0';
        $this->finTsOptions->bankCode = '70000000';
        $this->finTsOptions->url = 'https://testbank.de/fints';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product name required!');

        $this->finTsOptions->validate();
    }

    public function testValidateThrowsExceptionForWhitespaceOnlyProductName(): void
    {
        $this->finTsOptions->productName = '   ';
        $this->finTsOptions->productVersion = '1.0';
        $this->finTsOptions->bankCode = '70000000';
        $this->finTsOptions->url = 'https://testbank.de/fints';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product name required!');

        $this->finTsOptions->validate();
    }

    public function testValidateThrowsExceptionForEmptyProductVersion(): void
    {
        $this->finTsOptions->productName = 'TestProduct';
        $this->finTsOptions->productVersion = '';
        $this->finTsOptions->bankCode = '70000000';
        $this->finTsOptions->url = 'https://testbank.de/fints';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product version required!');

        $this->finTsOptions->validate();
    }

    public function testValidateThrowsExceptionForEmptyBankCode(): void
    {
        $this->finTsOptions->productName = 'TestProduct';
        $this->finTsOptions->productVersion = '1.0';
        $this->finTsOptions->bankCode = '';
        $this->finTsOptions->url = 'https://testbank.de/fints';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Bank code required!');

        $this->finTsOptions->validate();
    }

    public function testValidateThrowsExceptionForEmptyUrl(): void
    {
        $this->finTsOptions->productName = 'TestProduct';
        $this->finTsOptions->productVersion = '1.0';
        $this->finTsOptions->bankCode = '70000000';
        $this->finTsOptions->url = '';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Server URL required!');

        $this->finTsOptions->validate();
    }

    public function testValidateThrowsExceptionForWhitespaceOnlyUrl(): void
    {
        $this->finTsOptions->productName = 'TestProduct';
        $this->finTsOptions->productVersion = '1.0';
        $this->finTsOptions->bankCode = '70000000';
        $this->finTsOptions->url = '   ';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Server URL required!');

        $this->finTsOptions->validate();
    }

    public function testValidateWithUrlIncludingPort(): void
    {
        $this->finTsOptions->productName = 'TestProduct';
        $this->finTsOptions->productVersion = '1.0';
        $this->finTsOptions->bankCode = '70000000';
        $this->finTsOptions->url = 'https://testbank.de:8443/fints';

        $this->finTsOptions->validate();

        $this->assertSame('https://testbank.de:8443/fints', $this->finTsOptions->url);
    }

    public function testValidateWithHttpUrl(): void
    {
        $this->finTsOptions->productName = 'TestProduct';
        $this->finTsOptions->productVersion = '1.0';
        $this->finTsOptions->bankCode = '70000000';
        $this->finTsOptions->url = 'http://testbank.de/fints';

        // HTTP should be allowed (library doesn't enforce HTTPS)
        $this->finTsOptions->validate();

        $this->assertSame('http://testbank.de/fints', $this->finTsOptions->url);
    }
}

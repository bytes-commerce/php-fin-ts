<?php

namespace BytesCommerce\Tests\Unit\MT940\Dialect;

use BytesCommerce\MT940\Dialect\PostbankMT940;
use PHPUnit\Framework\TestCase;

final class PostbankMT940Test extends TestCase
{
    private PostbankMT940 $postbankMT940;

    protected function setUp(): void
    {
        $this->postbankMT940 = new PostbankMT940();
    }

    public function testDialectId(): void
    {
        $this->assertSame('https://hbci.postbank.de/banking/hbci.do', PostbankMT940::DIALECT_ID);
    }

    public function testExtractStructuredDataFromRemittanceLinesWithEmptyInput(): void
    {
        $gvc = '';
        $rawLines = [];
        $result = $this->postbankMT940->extractStructuredDataFromRemittanceLines([], $gvc, $rawLines, []);

        $this->assertSame([], $result);
    }

    public function testExtractStructuredDataFromRemittanceLinesWithStructuredData(): void
    {
        $descriptionLines = ['SVWZ+Test description'];
        $gvc = '166';
        $rawLines = [];
        $transaction = [];

        $result = $this->postbankMT940->extractStructuredDataFromRemittanceLines($descriptionLines, $gvc, $rawLines, $transaction);

        $this->assertArrayHasKey('SVWZ', $result);
    }

    public function testExtractStructuredDataFromRemittanceLinesWithInternationalTransfer(): void
    {
        $descriptionLines = ['Name', 'Description line'];
        $gvc = '210';
        $rawLines = [32 => '', 33 => ''];
        $transaction = [];

        $result = $this->postbankMT940->extractStructuredDataFromRemittanceLines($descriptionLines, $gvc, $rawLines, $transaction);

        $this->assertArrayHasKey('SVWZ', $result);
    }
}

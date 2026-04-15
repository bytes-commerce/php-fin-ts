<?php

namespace Fhp\Tests\Unit\MT940\Dialect;

use Fhp\MT940\Dialect\PostbankMT940;
use PHPUnit\Framework\TestCase;

final class PostbankMT940Test extends TestCase
{
    private PostbankMT940 $mt940;

    protected function setUp(): void
    {
        $this->mt940 = new PostbankMT940();
    }

    public function testDialectId(): void
    {
        $this->assertSame('https://hbci.postbank.de/banking/hbci.do', PostbankMT940::DIALECT_ID);
    }

    public function testExtractStructuredDataFromRemittanceLinesWithEmptyInput(): void
    {
        $gvc = '';
        $rawLines = [];
        $result = $this->mt940->extractStructuredDataFromRemittanceLines([], $gvc, $rawLines, []);

        $this->assertSame([], $result);
    }

    public function testExtractStructuredDataFromRemittanceLinesWithStructuredData(): void
    {
        $descriptionLines = ['SVWZ+Test description'];
        $gvc = '166';
        $rawLines = [];
        $transaction = [];

        $result = $this->mt940->extractStructuredDataFromRemittanceLines($descriptionLines, $gvc, $rawLines, $transaction);

        $this->assertArrayHasKey('SVWZ', $result);
    }

    public function testExtractStructuredDataFromRemittanceLinesWithInternationalTransfer(): void
    {
        $descriptionLines = ['Name', 'Description line'];
        $gvc = '210';
        $rawLines = [32 => '', 33 => ''];
        $transaction = [];

        $result = $this->mt940->extractStructuredDataFromRemittanceLines($descriptionLines, $gvc, $rawLines, $transaction);

        $this->assertArrayHasKey('SVWZ', $result);
    }
}

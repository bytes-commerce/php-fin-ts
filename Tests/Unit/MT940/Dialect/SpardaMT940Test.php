<?php

namespace Fhp\Tests\Unit\MT940\Dialect;

use Fhp\MT940\Dialect\SpardaMT940;
use PHPUnit\Framework\TestCase;

final class SpardaMT940Test extends TestCase
{
    private SpardaMT940 $mt940;

    protected function setUp(): void
    {
        $this->mt940 = new SpardaMT940();
    }

    public function testDialectId(): void
    {
        $this->assertSame('https://fints.bankingonline.de/fints/FinTs30PinTanHttpGate', SpardaMT940::DIALECT_ID);
    }

    public function testExtractStructuredDataFromRemittanceLinesWithEmptyInput(): void
    {
        $gvc = '';
        $rawLines = [];
        $result = $this->mt940->extractStructuredDataFromRemittanceLines([], $gvc, $rawLines, []);

        $this->assertIsArray($result);
    }

    public function testExtractStructuredDataFromRemittanceLinesWithStructuredData(): void
    {
        $descriptionLines = ['SVWZ+ Test description'];
        $gvc = '166';
        $rawLines = [];
        $transaction = ['credit_debit' => 'C'];

        $result = $this->mt940->extractStructuredDataFromRemittanceLines($descriptionLines, $gvc, $rawLines, $transaction);

        $this->assertArrayHasKey('SVWZ', $result);
    }

    public function testExtractStructuredDataFromRemittanceLinesWithoutStructuredData(): void
    {
        $descriptionLines = ['Some unstructured text'];
        $gvc = '166';
        $rawLines = [];
        $transaction = [];

        $result = $this->mt940->extractStructuredDataFromRemittanceLines($descriptionLines, $gvc, $rawLines, $transaction);

        $this->assertArrayHasKey('SVWZ', $result);
    }

    public function testExtractStructuredDataFromRemittanceLinesWithSepaCreditTransfer(): void
    {
        $descriptionLines = ['SEPA-UEBERWEISUNG', 'More details'];
        $gvc = '';
        $rawLines = [];
        $transaction = ['credit_debit' => 'C'];

        $result = $this->mt940->extractStructuredDataFromRemittanceLines($descriptionLines, $gvc, $rawLines, $transaction);

        $this->assertIsArray($result);
    }

    public function testExtractStructuredDataFromRemittanceLinesWithDauerauftrag(): void
    {
        $descriptionLines = ['SEPA-UEBERWEISUNG', 'Dauerauftrag text here'];
        $gvc = '';
        $rawLines = [];
        $transaction = ['credit_debit' => 'C'];

        $result = $this->mt940->extractStructuredDataFromRemittanceLines($descriptionLines, $gvc, $rawLines, $transaction);

        $this->assertIsArray($result);
    }
}

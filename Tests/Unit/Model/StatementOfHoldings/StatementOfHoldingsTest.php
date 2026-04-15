<?php

namespace Fhp\Tests\Unit\Model\StatementOfHoldings;

use Fhp\Model\StatementOfHoldings\Holding;
use Fhp\Model\StatementOfHoldings\StatementOfHoldings;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for StatementOfHoldings model class.
 * Tests the statement of holdings container.
 */
class StatementOfHoldingsTest extends TestCase
{
    private StatementOfHoldings $statement;

    protected function setUp(): void
    {
        $this->statement = new StatementOfHoldings();
    }

    public function testGetHoldingsInitiallyEmpty(): void
    {
        $this->assertIsArray($this->statement->getHoldings());
        $this->assertEmpty($this->statement->getHoldings());
    }

    public function testAddHoldingIncreasesCount(): void
    {
        $holding = new Holding();
        $holding->setName('Test Holding');

        $this->statement->addHolding($holding);

        $this->assertCount(1, $this->statement->getHoldings());
    }

    public function testAddMultipleHoldings(): void
    {
        $holding1 = new Holding();
        $holding1->setName('Holding 1');

        $holding2 = new Holding();
        $holding2->setName('Holding 2');

        $this->statement->addHolding($holding1);
        $this->statement->addHolding($holding2);

        $this->assertCount(2, $this->statement->getHoldings());
    }

    public function testGetHoldingsReturnsCorrectHolding(): void
    {
        $holding = new Holding();
        $holding->setISIN('DE0008404005');
        $holding->setName('Allianz SE');

        $this->statement->addHolding($holding);

        $holdings = $this->statement->getHoldings();
        $this->assertSame($holding, $holdings[0]);
        $this->assertSame('DE0008404005', $holdings[0]->getISIN());
        $this->assertSame('Allianz SE', $holdings[0]->getName());
    }

    public function testAddHoldingReturnsVoid(): void
    {
        $holding = new Holding();

        $result = $this->statement->addHolding($holding);

        // Method doesn't return anything (void), but we test it doesn't throw
        $this->assertNull($result);
    }

    public function testHoldingsArrayIsReference(): void
    {
        $holding1 = new Holding();
        $holding1->setName('Original');

        $this->statement->addHolding($holding1);

        // Modify the holding directly
        $holdings = $this->statement->getHoldings();
        $holdings[0]->setName('Modified');

        // The original holding should also be modified since it's the same reference
        $this->assertSame('Modified', $this->statement->getHoldings()[0]->getName());
    }
}

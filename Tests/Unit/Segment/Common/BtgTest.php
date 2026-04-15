<?php

namespace Fhp\Tests\Unit\Segment\Common;

use Fhp\Segment\Common\Btg;
use PHPUnit\Framework\TestCase;

final class BtgTest extends TestCase
{
    public function testCreate(): void
    {
        $btg = Btg::create(100.50, 'EUR');

        $this->assertSame(100.50, $btg->wert);
        $this->assertSame('EUR', $btg->waehrung);
    }

    public function testCreateWithDefaultCurrency(): void
    {
        $btg = Btg::create(50.00);

        $this->assertSame(50.00, $btg->wert);
        $this->assertSame('EUR', $btg->waehrung);
    }

    public function testGetWert(): void
    {
        $btg = Btg::create(123.45, 'USD');

        $this->assertSame(123.45, $btg->getWert());
    }

    public function testGetWaehrung(): void
    {
        $btg = Btg::create(99.99, 'GBP');

        $this->assertSame('GBP', $btg->getWaehrung());
    }

    public function testGetWertReturnsFloat(): void
    {
        $btg = Btg::create(0.01, 'EUR');

        $this->assertIsFloat($btg->getWert());
    }

    public function testGetWaehrungReturnsString(): void
    {
        $btg = Btg::create(1.00, 'JPY');

        $this->assertIsString($btg->getWaehrung());
    }
}

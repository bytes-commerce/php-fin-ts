<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Segment\Common;

use BytesCommerce\Segment\Common\Tsp;
use PHPUnit\Framework\TestCase;

final class TspTest extends TestCase
{
    public function testCreate(): void
    {
        $tsp = Tsp::create('20240115', '123045');

        $this->assertSame('20240115', $tsp->datum);
        $this->assertSame('123045', $tsp->uhrzeit);
    }

    public function testCreateWithNullUhrzeit(): void
    {
        $tsp = Tsp::create('20240115', null);

        $this->assertSame('20240115', $tsp->datum);
        $this->assertNull($tsp->uhrzeit);
    }

    public function testGetDatum(): void
    {
        $tsp = Tsp::create('20241225', '000000');

        $this->assertSame('20241225', $tsp->getDatum());
    }

    public function testGetUhrzeit(): void
    {
        $tsp = Tsp::create('20241225', '143000');

        $this->assertSame('143000', $tsp->getUhrzeit());
    }

    public function testGetUhrzeitReturnsNullWhenNotSet(): void
    {
        $tsp = Tsp::create('20241225', null);

        $this->assertNull($tsp->getUhrzeit());
    }

    public function testAsDateTime(): void
    {
        $tsp = Tsp::create('20240115', '123045');

        $result = $tsp->asDateTime();

        $this->assertInstanceOf(\DateTime::class, $result);
    }
}

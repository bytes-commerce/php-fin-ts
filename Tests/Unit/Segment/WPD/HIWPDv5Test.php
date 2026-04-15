<?php

namespace Fhp\Tests\Unit\Segment\WPD;

use Fhp\Segment\WPD\HIWPDv5;
use Fhp\Syntax\Bin;
use PHPUnit\Framework\TestCase;

final class HIWPDv5Test extends TestCase
{
    public function testGetDepotaufstellung(): void
    {
        $hiwpd = new HIWPDv5();
        $bin = new Bin('test data');
        $hiwpd->depotaufstellung = $bin;

        $this->assertSame($bin, $hiwpd->getDepotaufstellung());
    }

    public function testDepotaufstellungAssignment(): void
    {
        $hiwpd = new HIWPDv5();
        $data = 'MT353 data content';
        $hiwpd->depotaufstellung = new Bin($data);

        $this->assertSame($data, $hiwpd->depotaufstellung->getData());
    }
}

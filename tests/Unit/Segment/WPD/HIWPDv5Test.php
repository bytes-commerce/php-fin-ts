<?php

namespace BytesCommerce\Tests\Unit\Segment\WPD;

use BytesCommerce\Segment\WPD\HIWPDv5;
use BytesCommerce\Syntax\Bin;
use PHPUnit\Framework\TestCase;

final class HIWPDv5Test extends TestCase
{
    public function testGetDepotaufstellung(): void
    {
        $hiwpDv5 = new HIWPDv5();
        $bin = new Bin('test data');
        $hiwpDv5->depotaufstellung = $bin;

        $this->assertSame($bin, $hiwpDv5->getDepotaufstellung());
    }

    public function testDepotaufstellungAssignment(): void
    {
        $hiwpDv5 = new HIWPDv5();
        $data = 'MT353 data content';
        $hiwpDv5->depotaufstellung = new Bin($data);

        $this->assertSame($data, $hiwpDv5->depotaufstellung->getData());
    }
}

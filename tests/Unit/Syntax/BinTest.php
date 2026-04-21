<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Syntax;

use BytesCommerce\Syntax\Bin;

class BinTest extends \PHPUnit\Framework\TestCase
{
    public function testToString(): void
    {
        $string = md5(uniqid());
        $string2 = md5(uniqid());

        $bin = new Bin($string);
        $this->assertEquals('@32@' . $string, (string) $bin);
        $this->assertEquals('@32@' . $string, $bin->toString());
        $this->assertEquals($string, $bin->getData());

        $bin->setData($string2);
        $this->assertEquals('@32@' . $string2, (string) $bin);
        $this->assertEquals('@32@' . $string2, $bin->toString());
        $this->assertEquals($string2, $bin->getData());
    }
}

<?php

namespace BytesCommerce\Tests\Unit\Segment;

use BytesCommerce\Segment\Common\Kti;
use BytesCommerce\Segment\Common\Sdo;
use BytesCommerce\Segment\SAL\HISALv7;

class HISALTest extends \PHPUnit\Framework\TestCase
{
    public const REAL_DKB_RESPONSE = "HITAB:1:4:3+0+A:1:::::::::::pushtan::::::::+A:1:::::::::::SomePhone1::::::::'";

    public function testEmptyKti(): void
    {
        $hisaLv7 = HISALv7::createEmpty();
        $hisaLv7->segmentkopf->segmentnummer = 11;
        $hisaLv7->kontoverbindungInternational = new Kti();  // Note that none of its fields are filled.
        $hisaLv7->kontoproduktbezeichnung = 'Test';
        $hisaLv7->kontowaehrung = 'EUR';
        $hisaLv7->gebuchterSaldo = Sdo::create(42, 'EUR', \DateTime::createFromFormat('Ymd His', '20200102 030405'));

        $serialized = $hisaLv7->serialize();
        $this->assertEquals("HISAL:11:7++Test+EUR+C:42,:EUR:20200102:030405'", $serialized);
        $parsed = HISALv7::parse($serialized);
        $this->assertEquals($hisaLv7->serialize(), $parsed->serialize());
    }
}

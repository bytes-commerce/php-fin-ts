<?php

namespace BytesCommerce\Tests\Unit\Segment\Common;

use BytesCommerce\Segment\Common\Kursqualitaet;
use PHPUnit\Framework\TestCase;

final class KursqualitaetTest extends TestCase
{
    public function testGetKursqualitaetWithDelayed(): void
    {
        $kursqualitaet = new Kursqualitaet();
        $kursqualitaet->kursqualitaet = Kursqualitaet::DELAYED;

        $this->assertSame(Kursqualitaet::DELAYED, $kursqualitaet->getKursqualitaet());
    }

    public function testGetKursqualitaetWithRealtime(): void
    {
        $kursqualitaet = new Kursqualitaet();
        $kursqualitaet->kursqualitaet = Kursqualitaet::REALTIME;

        $this->assertSame(Kursqualitaet::REALTIME, $kursqualitaet->getKursqualitaet());
    }

    public function testConstants(): void
    {
        $this->assertSame(1, Kursqualitaet::DELAYED);
        $this->assertSame(2, Kursqualitaet::REALTIME);
    }
}

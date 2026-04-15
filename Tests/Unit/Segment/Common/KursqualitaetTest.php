<?php

namespace Fhp\Tests\Unit\Segment\Common;

use Fhp\Segment\Common\Kursqualitaet;
use PHPUnit\Framework\TestCase;

final class KursqualitaetTest extends TestCase
{
    public function testGetKursqualitaetWithDelayed(): void
    {
        $kq = new Kursqualitaet();
        $kq->kursqualitaet = Kursqualitaet::DELAYED;

        $this->assertSame(Kursqualitaet::DELAYED, $kq->getKursqualitaet());
    }

    public function testGetKursqualitaetWithRealtime(): void
    {
        $kq = new Kursqualitaet();
        $kq->kursqualitaet = Kursqualitaet::REALTIME;

        $this->assertSame(Kursqualitaet::REALTIME, $kq->getKursqualitaet());
    }

    public function testConstants(): void
    {
        $this->assertSame(1, Kursqualitaet::DELAYED);
        $this->assertSame(2, Kursqualitaet::REALTIME);
    }
}

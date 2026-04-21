<?php

namespace BytesCommerce\Tests\Unit\Segment;

use BytesCommerce\Segment\HIUPD\HIUPDv4;

class HIUPDTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/archiv/HBCI_V2.x_FV.zip
     * File: HBCI22 Final.pdf
     * Search for: "HIUPD:"
     */
    public const HBCI22_EXAMPLES = [
        // NOTE: These two examples are likely outdated in the document because the new $unterkontomerkmal field was
        // added. So it's `::280` and not just `:280`. The first of these two examples occurs twice in the document,
        // once in the correct format. Here both of them are "fixed" (hopefully).
        // NOTE: These examples are UTF-8 encoded in the source code, but the real wire format is ISO-8859-1 encoded,
        // so they need to be passed through mb_convert_encoding() before being used.
        "HIUPD:16:4:4+1234567::280:10020030+12345+DEM+Ernst Müller++Giro Spezial+T:2000,:DEM+HKPRO:1+HKSAK:1+HKISA:1+HKSSP:1+HKUEB:1+HKLAS:1+HKKAN:1+HKKAZ:1+HKSAL:1'",
        "HIUPD:17:4:4+1234568::280:10020030+12345+DEM+Ernst Müller++Sparkonto 2000++HKPRO:1+HKSAK:0+HKISA:1+HKSSP:0+HKUEB:2:Z:1000,:DEM:7+HKKAN:1+HKKAZ:1+HKSAL:2'",
    ];

    public function testParseHBCI22Example1(): void
    {
        $hiupDv4 = HIUPDv4::parse(mb_convert_encoding(static::HBCI22_EXAMPLES[0], 'ISO-8859-1', 'UTF-8'));
        $this->assertSame(16, $hiupDv4->segmentkopf->segmentnummer);
        $this->assertSame(4, $hiupDv4->segmentkopf->segmentversion);
        $this->assertSame('1234567', $hiupDv4->kontoverbindung->kontonummer);
        $this->assertNull($hiupDv4->kontoverbindung->unterkontomerkmal);
        $this->assertSame('280', $hiupDv4->kontoverbindung->kik->laenderkennzeichen);
        $this->assertSame('10020030', $hiupDv4->kontoverbindung->kik->kreditinstitutscode);
        $this->assertSame('12345', $hiupDv4->kundenId);
        $this->assertSame('DEM', $hiupDv4->kontowaehrung);
        $this->assertSame('Ernst Müller', $hiupDv4->name1);
        $this->assertSame('Giro Spezial', $hiupDv4->kontoproduktbezeichnung);

        $this->assertSame('T', $hiupDv4->kontolimit->limitart);
        $this->assertSame(2000.0, $hiupDv4->kontolimit->limitbetrag->wert);
        $this->assertSame('DEM', $hiupDv4->kontolimit->limitbetrag->waehrung);
        $this->assertNull($hiupDv4->kontolimit->limitTage);

        $this->assertCount(9, $hiupDv4->erlaubteGeschaeftsvorfaelle);
    }

    public function testValidateHBCI22Example1(): void
    {
        $hiupDv4 = HIUPDv4::parse(mb_convert_encoding(static::HBCI22_EXAMPLES[0], 'ISO-8859-1', 'UTF-8'));
        $hiupDv4->validate(); // Should not throw.
        $this->assertTrue(true);
    }

    public function testSerializeHBCI22Example1(): void
    {
        $hiupDv4 = HIUPDv4::parse(mb_convert_encoding(static::HBCI22_EXAMPLES[0], 'ISO-8859-1', 'UTF-8'));
        $this->assertEquals(mb_convert_encoding(static::HBCI22_EXAMPLES[0], 'ISO-8859-1', 'UTF-8'), $hiupDv4->serialize());
    }

    public function testParseHBCI22Example2(): void
    {
        $hiupDv4 = HIUPDv4::parse(mb_convert_encoding(static::HBCI22_EXAMPLES[1], 'ISO-8859-1', 'UTF-8'));
        $this->assertSame('1234568', $hiupDv4->kontoverbindung->kontonummer);
        $this->assertSame('Sparkonto 2000', $hiupDv4->kontoproduktbezeichnung);
        $this->assertNull($hiupDv4->kontolimit);

        $this->assertCount(8, $hiupDv4->erlaubteGeschaeftsvorfaelle);
        $this->assertSame('HKUEB', $hiupDv4->erlaubteGeschaeftsvorfaelle[4]->geschaeftsvorfall);
        $this->assertSame(2, $hiupDv4->erlaubteGeschaeftsvorfaelle[4]->anzahlBenoetigterSignaturen);
        $this->assertSame('Z', $hiupDv4->erlaubteGeschaeftsvorfaelle[4]->limitart);
        $this->assertSame(1000.0, $hiupDv4->erlaubteGeschaeftsvorfaelle[4]->limitbetrag->wert);
        $this->assertSame('DEM', $hiupDv4->erlaubteGeschaeftsvorfaelle[4]->limitbetrag->waehrung);
        $this->assertSame(7, $hiupDv4->erlaubteGeschaeftsvorfaelle[4]->limitTage);
    }

    public function testValidateHBCI22Example2(): void
    {
        $hiupDv4 = HIUPDv4::parse(mb_convert_encoding(static::HBCI22_EXAMPLES[1], 'ISO-8859-1', 'UTF-8'));
        $hiupDv4->validate(); // Should not throw.
        $this->assertTrue(true);
    }

    public function testSerializeHBCI22Example2(): void
    {
        $hiupDv4 = HIUPDv4::parse(mb_convert_encoding(static::HBCI22_EXAMPLES[1], 'ISO-8859-1', 'UTF-8'));
        $this->assertEquals(mb_convert_encoding(static::HBCI22_EXAMPLES[1], 'ISO-8859-1', 'UTF-8'), $hiupDv4->serialize());
    }
}

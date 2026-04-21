<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\WPD;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\Paginateable;

/**
 * Segment: Depotaufstellung anfordern (Version 5)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: C.4.3.1a
 */
class HKWPDv5 extends BaseSegment implements Paginateable
{
    public \BytesCommerce\Segment\Common\KtvV3 $depot;

    public ?string $waehrungDerDepotaufstellung = null;

    public ?\BytesCommerce\Segment\Common\Kursqualitaet $kursqualitaet = null;

    public ?int $maximaleAnzahlEintraege = null;

    public ?string $aufsetzpunkt = null;

    public function getDepot(): \BytesCommerce\Segment\Common\KtvV3
    {
        return $this->depot;
    }

    public function getWaehrungDerDepotaufstellung(): ?string
    {
        return $this->waehrungDerDepotaufstellung;
    }

    public function getKursqualitaet(): ?\BytesCommerce\Segment\Common\Kursqualitaet
    {
        return $this->kursqualitaet;
    }

    public function getMaximaleAnzahlEintraege(): ?int
    {
        return $this->maximaleAnzahlEintraege;
    }

    public function getAufsetzpunkt(): ?string
    {
        return $this->aufsetzpunkt;
    }

    public static function create(\BytesCommerce\Segment\Common\KtvV3 $ktvV3): HKWPDv5
    {
        $hkwpDv5 = HKWPDv5::createEmpty();
        $hkwpDv5->depot = $ktvV3;
        return $hkwpDv5;
    }

    public function setPaginationToken(string $paginationToken): void
    {
        $this->aufsetzpunkt = $paginationToken;
    }
}

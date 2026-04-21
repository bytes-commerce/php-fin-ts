<?php

declare(strict_types=1);


/** @noinspection PhpUnused */

namespace BytesCommerce\Segment\KAZ;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\Paginateable;

/**
 * Segment: Kontoumsätze anfordern/Zeitraum (Version 7)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: C.2.1.1.1.2
 */
class HKKAZv7 extends BaseSegment implements Paginateable
{
    public \BytesCommerce\Segment\Common\Kti $kontoverbindungInternational;

    public bool $alleKonten;

    public ?string $vonDatum = null;

    public ?string $bisDatum = null;

    public ?int $maximaleAnzahlEintraege = null;

    public ?string $aufsetzpunkt = null;

    public function getKontoverbindungInternational(): \BytesCommerce\Segment\Common\Kti
    {
        return $this->kontoverbindungInternational;
    }

    public function getAlleKonten(): bool
    {
        return $this->alleKonten;
    }

    public function getVonDatum(): ?string
    {
        return $this->vonDatum;
    }

    public function getBisDatum(): ?string
    {
        return $this->bisDatum;
    }

    public function getMaximaleAnzahlEintraege(): ?int
    {
        return $this->maximaleAnzahlEintraege;
    }

    public function getAufsetzpunkt(): ?string
    {
        return $this->aufsetzpunkt;
    }

    public static function create(\BytesCommerce\Segment\Common\Kti $kti, bool $alleKonten, ?\DateTime $vonDatum, ?\DateTime $bisDatum, ?string $aufsetzpunkt = null): HKKAZv7
    {
        $hkkaZv7 = HKKAZv7::createEmpty();
        $hkkaZv7->kontoverbindungInternational = $kti;
        $hkkaZv7->alleKonten = $alleKonten;
        $hkkaZv7->vonDatum = $vonDatum?->format('Ymd');
        $hkkaZv7->bisDatum = $bisDatum?->format('Ymd');
        $hkkaZv7->aufsetzpunkt = $aufsetzpunkt;
        return $hkkaZv7;
    }

    public function setPaginationToken(string $paginationToken): void
    {
        $this->aufsetzpunkt = $paginationToken;
    }
}

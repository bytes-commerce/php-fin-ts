<?php
/** @noinspection PhpUnused */

namespace Fhp\Segment\KAZ;

use Fhp\Segment\BaseSegment;
use Fhp\Segment\Paginateable;

/**
 * Segment: Kontoumsätze anfordern/Zeitraum (Version 7)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: C.2.1.1.1.2
 */
class HKKAZv7 extends BaseSegment implements Paginateable
{
    public \Fhp\Segment\Common\Kti $kontoverbindungInternational;
    public bool $alleKonten;
    public ?string $vonDatum = null;
    public ?string $bisDatum = null;
    public ?int $maximaleAnzahlEintraege = null;
    public ?string $aufsetzpunkt = null;

    public function getKontoverbindungInternational(): \Fhp\Segment\Common\Kti
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

    public static function create(\Fhp\Segment\Common\Kti $kti, bool $alleKonten, ?\DateTime $vonDatum, ?\DateTime $bisDatum, ?string $aufsetzpunkt = null): HKKAZv7
    {
        $result = HKKAZv7::createEmpty();
        $result->kontoverbindungInternational = $kti;
        $result->alleKonten = $alleKonten;
        $result->vonDatum = $vonDatum?->format('Ymd');
        $result->bisDatum = $bisDatum?->format('Ymd');
        $result->aufsetzpunkt = $aufsetzpunkt;
        return $result;
    }

    public function setPaginationToken(string $paginationToken)
    {
        $this->aufsetzpunkt = $paginationToken;
    }
}

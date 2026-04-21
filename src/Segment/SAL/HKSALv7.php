<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\SAL;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\Paginateable;

/**
 * Segment: Saldenabfrage (Version 7)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: C.2.1.2.2 a)
 */
class HKSALv7 extends BaseSegment implements Paginateable
{
    public \BytesCommerce\Segment\Common\Kti $kontoverbindungInternational;

    public bool $alleKonten;

    public ?int $maximaleAnzahlEintraege = null;

    /** Max length: 35 */
    public ?string $aufsetzpunkt = null;

    public static function create(\BytesCommerce\Segment\Common\Kti $kti, bool $alleKonten, ?string $aufsetzpunkt = null): HKSALv7
    {
        $hksaLv7 = HKSALv7::createEmpty();
        $hksaLv7->kontoverbindungInternational = $kti;
        $hksaLv7->alleKonten = $alleKonten;
        $hksaLv7->aufsetzpunkt = $aufsetzpunkt;
        return $hksaLv7;
    }

    public function setPaginationToken(string $paginationToken): void
    {
        $this->aufsetzpunkt = $paginationToken;
    }
}

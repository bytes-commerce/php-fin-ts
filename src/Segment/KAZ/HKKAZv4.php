<?php

declare(strict_types=1);


/** @noinspection PhpUnused */

namespace BytesCommerce\Segment\KAZ;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\Paginateable;

/**
 * Segment: Kontoumsätze anfordern/Zeitraum (Version 4)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/archiv/HBCI_V2.x_FV.zip
 * File: Gesamtdok_HBCI210.pdf
 * Section: VII.2.1.1 a)
 */
class HKKAZv4 extends BaseSegment implements Paginateable
{
    public \BytesCommerce\Segment\Common\Kto $kontoverbindungAuftraggeber;

    public ?string $kontowaehrung = null;

    /** JJJJMMTT gemäß ISO 8601 */
    public ?string $vonDatum = null;

    /** JJJJMMTT gemäß ISO 8601 */
    public ?string $bisDatum = null;

    /** Only allowed if {@link ParameterKontoumsaetzeV1::$eingabeAnzahlEintraegeErlaubt} says so. */
    public ?int $maximaleAnzahlEintraege = null;

    /** Max length: 35 */
    public ?string $aufsetzpunkt = null;

    public static function create(\BytesCommerce\Segment\Common\Kto $kto, ?\DateTime $vonDatum, ?\DateTime $bisDatum, ?string $aufsetzpunkt = null): HKKAZv4
    {
        $hkkaZv4 = HKKAZv4::createEmpty();
        $hkkaZv4->kontoverbindungAuftraggeber = $kto;
        $hkkaZv4->vonDatum = $vonDatum?->format('Ymd');
        $hkkaZv4->bisDatum = $bisDatum?->format('Ymd');
        $hkkaZv4->aufsetzpunkt = $aufsetzpunkt;
        return $hkkaZv4;
    }

    public function setPaginationToken(string $paginationToken): void
    {
        $this->aufsetzpunkt = $paginationToken;
    }
}

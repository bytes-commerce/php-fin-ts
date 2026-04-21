<?php

declare(strict_types=1);


/** @noinspection PhpUnused */

namespace BytesCommerce\Segment\KAZ;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\Paginateable;

/**
 * Segment: Kontoumsätze anfordern/Zeitraum (Version 5)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/archiv/HBCI_V2.x_FV.zip
 * File: HBCI22 Final.pdf
 * Section: VII.2.1.1 a)
 */
class HKKAZv5 extends BaseSegment implements Paginateable
{
    public \BytesCommerce\Segment\Common\KtvV3 $kontoverbindungAuftraggeber;

    /** Only allowed if {@link ParameterKontoumsaetzeV2::$alleKontenErlaubt} says so. */
    public bool $alleKonten;

    /** JJJJMMTT gemäß ISO 8601 */
    public ?string $vonDatum = null;

    /** JJJJMMTT gemäß ISO 8601 */
    public ?string $bisDatum = null;

    /** Only allowed if {@link ParameterKontoumsaetzeV2::$eingabeAnzahlEintraegeErlaubt} says so. */
    public ?int $maximaleAnzahlEintraege = null;

    /** Max length: 35 */
    public ?string $aufsetzpunkt = null;

    public static function create(\BytesCommerce\Segment\Common\KtvV3 $ktvV3, bool $alleKonten, ?\DateTime $vonDatum, ?\DateTime $bisDatum, ?string $aufsetzpunkt = null): HKKAZv5
    {
        $hkkaZv5 = HKKAZv5::createEmpty();
        $hkkaZv5->kontoverbindungAuftraggeber = $ktvV3;
        $hkkaZv5->alleKonten = $alleKonten;
        $hkkaZv5->vonDatum = $vonDatum?->format('Ymd');
        $hkkaZv5->bisDatum = $bisDatum?->format('Ymd');
        $hkkaZv5->aufsetzpunkt = $aufsetzpunkt;
        return $hkkaZv5;
    }

    public function setPaginationToken(string $paginationToken): void
    {
        $this->aufsetzpunkt = $paginationToken;
    }
}

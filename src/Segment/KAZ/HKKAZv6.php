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
 * Section: C.2.1.1.1.1 a)
 */
class HKKAZv6 extends BaseSegment implements Paginateable
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

    public static function create(\BytesCommerce\Segment\Common\KtvV3 $ktvV3, bool $alleKonten, ?\DateTime $vonDatum, ?\DateTime $bisDatum, ?string $aufsetzpunkt = null): HKKAZv6
    {
        $hkkaZv6 = HKKAZv6::createEmpty();
        $hkkaZv6->kontoverbindungAuftraggeber = $ktvV3;
        $hkkaZv6->alleKonten = $alleKonten;
        $hkkaZv6->vonDatum = $vonDatum?->format('Ymd');
        $hkkaZv6->bisDatum = $bisDatum?->format('Ymd');
        $hkkaZv6->aufsetzpunkt = $aufsetzpunkt;
        return $hkkaZv6;
    }

    public function setPaginationToken(string $paginationToken): void
    {
        $this->aufsetzpunkt = $paginationToken;
    }
}

<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\SAL;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\Paginateable;

/**
 * Segment: Saldenabfrage (Version 6)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: C.2.1.2.1 a)
 */
class HKSALv6 extends BaseSegment implements Paginateable
{
    public \BytesCommerce\Segment\Common\KtvV3 $kontoverbindungAuftraggeber;

    public bool $alleKonten;

    public ?int $maximaleAnzahlEintraege = null;

    /** Max length: 35 */
    public ?string $aufsetzpunkt = null;

    public static function create(\BytesCommerce\Segment\Common\KtvV3 $ktvV3, bool $alleKonten, ?string $aufsetzpunkt = null): HKSALv6
    {
        $hksaLv6 = HKSALv6::createEmpty();
        $hksaLv6->kontoverbindungAuftraggeber = $ktvV3;
        $hksaLv6->alleKonten = $alleKonten;
        $hksaLv6->aufsetzpunkt = $aufsetzpunkt;
        return $hksaLv6;
    }

    public function setPaginationToken(string $paginationToken): void
    {
        $this->aufsetzpunkt = $paginationToken;
    }
}

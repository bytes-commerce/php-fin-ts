<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\SAL;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\Paginateable;

/**
 * Segment: Saldenabfrage (Version 5)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/archiv/HBCI_V2.x_FV.zip
 * File: HBCI22 Final.pdf
 * Section: VII.2.2 a)
 */
class HKSALv5 extends BaseSegment implements Paginateable
{
    public \BytesCommerce\Segment\Common\KtvV3 $kontoverbindungAuftraggeber;

    public bool $alleKonten;

    public ?int $maximaleAnzahlEintraege = null;

    /** Max length: 35 */
    public ?string $aufsetzpunkt = null;

    public static function create(\BytesCommerce\Segment\Common\KtvV3 $ktvV3, bool $alleKonten, ?string $aufsetzpunkt = null): HKSALv5
    {
        $hksaLv5 = HKSALv5::createEmpty();
        $hksaLv5->kontoverbindungAuftraggeber = $ktvV3;
        $hksaLv5->alleKonten = $alleKonten;
        $hksaLv5->aufsetzpunkt = $aufsetzpunkt;
        return $hksaLv5;
    }

    public function setPaginationToken(string $paginationToken): void
    {
        $this->aufsetzpunkt = $paginationToken;
    }
}

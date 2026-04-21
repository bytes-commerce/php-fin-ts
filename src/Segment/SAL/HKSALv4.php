<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\SAL;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\Paginateable;

/**
 * Segment: Saldenabfrage (Version 4)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/archiv/HBCI_V2.x_FV.zip
 * File: Gesamtdok_HBCI21o.pdf
 * Section: VII.2.2 a)
 */
class HKSALv4 extends BaseSegment implements Paginateable
{
    public \BytesCommerce\Segment\Common\Kto $kontoverbindungAuftraggeber;

    public bool $alleKonten;

    public ?string $kontowaehrung = null;

    public ?int $maximaleAnzahlEintraege = null;

    /** Max length: 35 */
    public ?string $aufsetzpunkt = null;

    public static function create(\BytesCommerce\Segment\Common\Kto $kto, ?string $aufsetzpunkt = null): HKSALv4
    {
        $hksaLv4 = HKSALv4::createEmpty();
        $hksaLv4->kontoverbindungAuftraggeber = $kto;
        $hksaLv4->aufsetzpunkt = $aufsetzpunkt;
        return $hksaLv4;
    }

    public function setPaginationToken(string $paginationToken): void
    {
        $this->aufsetzpunkt = $paginationToken;
    }
}

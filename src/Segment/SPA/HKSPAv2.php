<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\SPA;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\Paginateable;

/**
 * Segment: SEPA-Kontoverbindung anfordern (Version 2)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section C.10.1.4 a)
 */
class HKSPAv2 extends BaseSegment implements Paginateable
{
    /**
     * If left empty, all accounts will be returned.
     * @var \BytesCommerce\Segment\Common\KtvV3[]|null @Max(999)
     */
    public ?array $kontoverbindung = null;

    /** Only allowed if {@link ParameterSepaKontoverbindungAnfordernV2::$eingabeAnzahlEintraegeErlaubt} says so. */
    public ?int $maximaleAnzahlEintraege = null;

    /** For pagination. */
    public ?string $aufsetzpunkt = null;

    public function setPaginationToken(string $paginationToken): void
    {
        $this->aufsetzpunkt = $paginationToken;
    }
}

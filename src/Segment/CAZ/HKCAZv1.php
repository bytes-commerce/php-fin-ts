<?php

declare(strict_types=1);


/** @noinspection PhpUnused */

namespace BytesCommerce\Segment\CAZ;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\Paginateable;

/**
 * Segment: Kontoumsätze/Zeitraum (camt)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: C.2.3.1.1.1
 */
class HKCAZv1 extends BaseSegment implements Paginateable
{
    public \BytesCommerce\Segment\Common\Kti $kontoverbindungInternational;

    public UnterstuetzteCamtMessages $unterstuetzteCamtMessages;

    /** Only allowed if {@link ParameterKontoumsaetzeCamt::$alleKontenErlaubt} says so. */
    public bool $alleKonten;

    /** JJJJMMTT gemäß ISO 8601 */
    public ?string $vonDatum = null;

    /** JJJJMMTT gemäß ISO 8601 */
    public ?string $bisDatum = null;

    /** Only allowed if {@link ParameterKontoumsaetzeCamt::$eingabeAnzahlEintraegeErlaubt} says so. */
    public ?int $maximaleAnzahlEintraege = null;

    /** Max length: 35 */
    public ?string $aufsetzpunkt = null;

    public static function create(\BytesCommerce\Segment\Common\Kti $kti, UnterstuetzteCamtMessages $unterstuetzteCamtMessages,
        bool $alleKonten, ?\DateTime $vonDatum, ?\DateTime $bisDatum, ?string $aufsetzpunkt = null): HKCAZv1
    {
        $hkcaZv1 = HKCAZv1::createEmpty();
        $hkcaZv1->kontoverbindungInternational = $kti;
        $hkcaZv1->unterstuetzteCamtMessages = $unterstuetzteCamtMessages;
        $hkcaZv1->alleKonten = $alleKonten;
        $hkcaZv1->vonDatum = $vonDatum?->format('Ymd');
        $hkcaZv1->bisDatum = $bisDatum?->format('Ymd');
        $hkcaZv1->aufsetzpunkt = $aufsetzpunkt;

        return $hkcaZv1;
    }

    public function setPaginationToken(string $paginationToken): void
    {
        $this->aufsetzpunkt = $paginationToken;
    }
}

<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\SAL;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\Common\AccountInfo;

/**
 * Segment: Saldenabfrage (Version 7)
 *
 * There will be one segment instance per account.
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: C.2.1.2.2 b)
 */
class HISALv7 extends BaseSegment implements HISAL
{
    public \BytesCommerce\Segment\Common\Kti $kontoverbindungInternational;

    public string $kontoproduktbezeichnung;

    public string $kontowaehrung;

    public \BytesCommerce\Segment\Common\Sdo $gebuchterSaldo;

    public ?\BytesCommerce\Segment\Common\Sdo $saldoDerVorgemerktenUmsaetze = null;

    public ?\BytesCommerce\Segment\Common\Btg $kreditlinie = null;

    public ?\BytesCommerce\Segment\Common\Btg $verfuegbarerBetrag = null;

    public ?\BytesCommerce\Segment\Common\Btg $bereitsVerfuegterBetrag = null;

    /** This field can only be filled if {@link HISALv7::$verfuegbarerBetrag} is zero. */
    public ?\BytesCommerce\Segment\Common\Btg $ueberziehung = null;

    public ?\BytesCommerce\Segment\Common\Tsp $buchungszeitpunkt = null;

    /** JJJJMMTT gemäß ISO 8601 */
    public ?string $faelligkeit = null;

    public function getAccountInfo(): AccountInfo
    {
        return $this->kontoverbindungInternational;
    }

    public function getKontoproduktbezeichnung(): string
    {
        return $this->kontoproduktbezeichnung;
    }

    public function getGebuchterSaldo(): \BytesCommerce\Segment\Common\Sdo
    {
        return $this->gebuchterSaldo;
    }

    public function getSaldoDerVorgemerktenUmsaetze(): ?\BytesCommerce\Segment\Common\Sdo
    {
        return $this->saldoDerVorgemerktenUmsaetze;
    }

    public function getKreditlinie(): ?\BytesCommerce\Segment\Common\Btg
    {
        return $this->kreditlinie;
    }

    public function getVerfuegbarerBetrag(): ?\BytesCommerce\Segment\Common\Btg
    {
        return $this->verfuegbarerBetrag;
    }

    public function getBereitsVerfuegterBetrag(): ?\BytesCommerce\Segment\Common\Btg
    {
        return $this->bereitsVerfuegterBetrag;
    }

    public function getBuchungszeitpunkt(): ?\BytesCommerce\Segment\Common\Tsp
    {
        return $this->buchungszeitpunkt;
    }

    public function getFaelligkeit(): ?string
    {
        return $this->faelligkeit;
    }
}

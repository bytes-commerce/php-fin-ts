<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\SAL;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\Common\AccountInfo;

/**
 * Segment: Saldenabfrage (Version 4)
 *
 * There will be one segment instance per account.
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/archiv/HBCI_V2.x_FV.zip
 * File: Gesamtdok_HBCI21o.pdf
 * Section: VII.2.2 b)
 */
class HISALv4 extends BaseSegment implements HISAL
{
    public \BytesCommerce\Segment\Common\Kto $kontoverbindungAuftraggeber;

    public string $kontoproduktbezeichnung;

    public string $kontowaehrung;

    public \BytesCommerce\Segment\Common\Sdo $gebuchterSaldo;

    public ?\BytesCommerce\Segment\Common\Sdo $saldoDerVorgemerktenUmsaetze = null;

    public ?\BytesCommerce\Segment\Common\Btg $kreditlinie = null;

    public ?\BytesCommerce\Segment\Common\Btg $verfuegbarerBetrag = null;

    public ?\BytesCommerce\Segment\Common\Btg $bereitsVerfuegterBetrag = null;

    /** JJJJMMTT gemäß ISO 8601 */
    public ?string $buchungsdatumDesSaldos = null;

    /** hhmmss gemäß ISO 8601, local time (no time zone support). */
    public ?string $buchungsuhrzeitDesSaldos = null;

    public function getAccountInfo(): AccountInfo
    {
        return $this->kontoverbindungAuftraggeber;
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
        return $this->buchungsdatumDesSaldos === null ? null :
            \BytesCommerce\Segment\Common\Tsp::create($this->buchungsdatumDesSaldos, $this->buchungsuhrzeitDesSaldos);
    }

    public function getFaelligkeit(): ?string
    {
        return null;
    }
}

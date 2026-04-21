<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\KAZ;

use BytesCommerce\Segment\BaseGeschaeftsvorfallparameterOld;

/**
 * Segment: Kontoumsätze/Zeitraum Parameter (Version 4)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/archiv/HBCI_V2.x_FV.zip
 * File: Gesamtdok_HBCI210.pdf
 * Section: VII.2.1.1 c)
 */
class HIKAZSv4 extends BaseGeschaeftsvorfallparameterOld implements HIKAZS
{
    public ParameterKontoumsaetzeV1 $parameter;

    public function getParameter(): ParameterKontoumsaetze
    {
        return $this->parameter;
    }
}

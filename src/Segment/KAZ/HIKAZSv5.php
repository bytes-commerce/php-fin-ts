<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\KAZ;

use BytesCommerce\Segment\BaseGeschaeftsvorfallparameterOld;

/**
 * Segment: Kontoumsätze/Zeitraum Parameter (Version 5)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/archiv/HBCI_V2.x_FV.zip
 * File: HBCI22 Final.pdf
 * Section: VII.2.1.1 c)
 */
class HIKAZSv5 extends BaseGeschaeftsvorfallparameterOld implements HIKAZS
{
    public ParameterKontoumsaetzeV2 $parameter;

    public function getParameter(): ParameterKontoumsaetze
    {
        return $this->parameter;
    }
}

<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\KAZ;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Syntax\Bin;

/**
 * Segment: Kontoumsätze rückmelden/Zeitraum (Version 4)
 *
 * There will be one segment instance per account.
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/archiv/HBCI_V2.x_FV.zip
 * File: Gesamtdok_HBCI210.pdf
 * Section: VII.2.1.1 b)
 */
class HIKAZv4 extends BaseSegment implements HIKAZ
{
    /** Uses SWIFT format MT940, version SRG 2001 */
    public Bin $gebuchteUmsaetze;

    /** Uses SWIFT format MT942, version SRG 2001 */
    public ?Bin $nichtGebuchteUmsaetze = null;

    public function getGebuchteUmsaetze(): Bin
    {
        return $this->gebuchteUmsaetze;
    }

    public function getNichtGebuchteUmsaetze(): ?Bin
    {
        return $this->nichtGebuchteUmsaetze;
    }
}

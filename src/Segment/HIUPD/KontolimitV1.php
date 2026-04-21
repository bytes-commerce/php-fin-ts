<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\HIUPD;

use BytesCommerce\Segment\BaseDeg;

/**
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/archiv/HBCI_V2.x_FV.zip
 * File: HBCI22 Final.pdf
 * Section: V.3 "Kontoinformation" > Nr. 8
 */
class KontolimitV1 extends BaseDeg
{
    /** Allowed values: E, T, W, M, Z */
    public string $limitart;

    public \BytesCommerce\Segment\Common\Btg $limitbetrag;

    /** If present, must be greater than 0 */
    public ?int $limitTage = null;
}

<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\KAZ;

use BytesCommerce\Segment\SegmentInterface;
use BytesCommerce\Syntax\Bin;

/**
 * Segment: Kontoumsätze rückmelden/Zeitraum
 */
interface HIKAZ extends SegmentInterface
{
    public function getGebuchteUmsaetze(): Bin;

    public function getNichtGebuchteUmsaetze(): ?Bin;
}

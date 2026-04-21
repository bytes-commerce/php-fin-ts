<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\KAZ;

use BytesCommerce\Segment\SegmentInterface;

/**
 * Segment: Kontoumsätze/Zeitraum Parameter
 */
interface HIKAZS extends SegmentInterface
{
    public function getParameter(): ParameterKontoumsaetze;
}

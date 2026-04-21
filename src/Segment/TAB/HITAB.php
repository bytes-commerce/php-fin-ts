<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\TAB;

use BytesCommerce\Segment\SegmentInterface;

/**
 * Segment: TAN-Generator/Liste anzeigen Bestand Rückmeldung
 */
interface HITAB extends SegmentInterface
{
    /** @return TanMediumListe[]|null */
    public function getTanMediumListe(): ?array;
}

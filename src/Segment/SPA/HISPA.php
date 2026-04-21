<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\SPA;

use BytesCommerce\Segment\SegmentInterface;

/**
 * Segment: SEPA-Kontoverbindung rückmelden
 */
interface HISPA extends SegmentInterface
{
    /** @return \BytesCommerce\Segment\Common\Ktz[] */
    public function getSepaKontoverbindung(): array;
}

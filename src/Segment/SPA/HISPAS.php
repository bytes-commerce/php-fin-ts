<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\SPA;

use BytesCommerce\Segment\SegmentInterface;

/**
 * Segment: SEPA-Kontoverbindung anfordern, Parameter
 */
interface HISPAS extends SegmentInterface
{
    public function getParameter(): ParameterSepaKontoverbindungAnfordern;
}

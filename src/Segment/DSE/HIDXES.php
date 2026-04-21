<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\DSE;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\SegmentInterface;

interface HIDXES extends SegmentInterface
{
    public function getParameter(): SEPADirectDebitMinimalLeadTimeProvider;

    public function createRequestSegment(): BaseSegment; // TODO Use more specific return type here?
}

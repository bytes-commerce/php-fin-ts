<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\HNHBS;

use BytesCommerce\Segment\BaseSegment;

/**
 * Segment: Nachrichtenabschluss (Version 1)
 */
class HNHBSv1 extends BaseSegment
{
    /** Must match the {@link HNHBKv2::$nachrichtennummer} in the same message. */
    public int $nachrichtennummer;
}

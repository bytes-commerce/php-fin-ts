<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\HIUPD;

/**
 * Data Element Group: Erlaubte Geschäftsvorfälle
 */
interface ErlaubteGeschaeftsvorfaelle
{
    /** @return string References a segment type name (Segmentkennung) */
    public function getGeschaeftsvorfall(): string;
}

<?php

namespace BytesCommerce\Tests\Unit\Segment;

use BytesCommerce\Segment\BaseDeg;
use BytesCommerce\Segment\BaseSegment;
use SebastianBergmann\Comparator\ObjectComparator;

/**
 * Comparator for sub-classes of {@link BaseSegment} and {@link BaseDeg} that ignores the descriptor private field.
 */
class SegmentComparator extends ObjectComparator
{
    public function accepts($expected, $actual)
    {
        if ($expected instanceof BaseSegment && $actual instanceof BaseSegment) {
            return true;
        }
        return $expected instanceof BaseDeg && $actual instanceof BaseDeg;
    }

    protected function toArray($object)
    {
        $array = parent::toArray($object);
        unset($array['descriptor']);
        return $array;
    }
}

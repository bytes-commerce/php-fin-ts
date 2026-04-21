<?php

use BytesCommerce\Tests\Unit\Segment\SegmentComparator;
use SebastianBergmann\Comparator\Factory;

Factory::getInstance()->register(new SegmentComparator());

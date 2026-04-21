<?php

declare(strict_types=1);

namespace BytesCommerce\Segment;

interface SegmentInterface
{
    /**
     * Returns string representation of object.
     */
    public function __toString(): string;

    /**
     * Gets the name of the segment.
     */
    public function getName(): string;

    public function getVersion(): int;

    public function getSegmentNumber(): int;
}

<?php

declare(strict_types=1);



namespace BytesCommerce\Protocol;

use BytesCommerce\Segment\BaseSegment;

/**
 * Collects segments and assigns them segment numbers in order to form a {@link Message}.
 */
class MessageBuilder
{
    /**
     * This is where the builder collects the (unencrypted/unwrapped) segments as they are being added.
     * @var BaseSegment[]
     */
    public $segments = [];

    /** @return MessageBuilder A new instance. */
    public static function create(): MessageBuilder
    {
        return new MessageBuilder();
    }

    /**
     * @param BaseSegment|BaseSegment[] $segments The segment(s) to be added.
     * @return $this The same instance for chaining.
     */
    public function add($segments): static
    {
        if (is_array($segments)) {
            foreach ($segments as $segment) {
                $this->addInternal($segment);
            }
        } else {
            $this->addInternal($segments);
        }

        return $this;
    }

    private function addInternal(BaseSegment $baseSegment): void
    {
        if (!$baseSegment->segmentkopf instanceof \BytesCommerce\Segment\Segmentkopf) {
            throw new \InvalidArgumentException(
                'Segment lacks Segmentkopf, maybe you called ctor instead of createEmpty()');
        }

        $this->segments[] = $baseSegment;
    }

    // Note: There is no single build() function, use Message::createWrappedMessage() instead.
}

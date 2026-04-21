<?php

declare(strict_types=1);



namespace BytesCommerce\Segment;

use BytesCommerce\Syntax\Delimiter;
use BytesCommerce\Syntax\Parser;

/**
 * A fallback for segments that were received from the server but are not implemented in this library.
 */
final class AnonymousSegment extends BaseSegment implements \Serializable
{
    /**
     * The type plus version of the segment, i.e. the class name of the class that would normally implement it.
     * This is redundant with super::$segmentkopf, but it's useful to repeat here so that it shows up in a debugger.
     */
    public string $type;

    /**
     * @param string[]|string[][] $elements
     */
    public function __construct(Segmentkopf $segmentkopf, /**
     * Contains the data elements of the segment. Some of them can be scalar values (represented as strings), and others
     * can be nested data element groups of strings.
     *
     * NOTE: This field is intentionally private and does not have a getter. Reading this field anywhere besides for
     * debugging purposes (e.g. in an interactive debugger or with print_r()) is wrong, please create a concrete
     * subclass of BaseSegment instead.
     */
    private array $elements)
    {
        $this->segmentkopf = $segmentkopf;
        $this->type = $segmentkopf->segmentkennung . 'v' . $segmentkopf->segmentversion;
    }

    public function getDescriptor(): SegmentDescriptor
    {
        throw new \RuntimeException('AnonymousSegments do not have a descriptor');
    }

    public function validate(): void
    {
        // Do nothing, anonymous segments are always valid.
    }

    /**
     * @deprecated Beginning from PHP7.4 __unserialize is used for new generated strings, then this method is only used for previously generated strings - remove after May 2023
     */
    public function serialize(): string
    {
        return $this->__serialize()[0];
    }

    /**
     * @deprecated Beginning from PHP7.4 __unserialize is used for new generated strings, then this method is only used for previously generated strings - remove after May 2023
     */
    public function unserialize($serialized): void
    {
        self::__unserialize([$serialized]);
    }

    public function __serialize(): array
    {
        $result = $this->segmentkopf->serialize() . Delimiter::ELEMENT .
            implode(Delimiter::ELEMENT, array_map(function (array|string|null $element): string {
                if ($element === null) {
                    return '';
                }

                if (is_string($element)) {
                    return $element;
                }

                return implode(Delimiter::GROUP, $element);
            }, $this->elements))
            . Delimiter::SEGMENT;

        return [$result];
    }

    public function __unserialize(array $serialized): void
    {
        $anonymousSegment = Parser::parseAnonymousSegment($serialized[0]);
        $this->type = $anonymousSegment->type;
        $this->segmentkopf = $anonymousSegment->segmentkopf;
        $this->elements = $anonymousSegment->elements;
    }

    /**
     * Just to override the super factory.
     * @noinspection PhpUnnecessaryStaticReferenceInspection
     */
    public static function createEmpty(): static
    {
        // Note: createEmpty() normally runs the constructor and then fills the Segmentkopf, but that is not possible
        // for AnonymousSegment. Callers should just call the constructor itself.
        throw new \RuntimeException('AnonymousSegment::createEmpty() is not allowed');
    }
}

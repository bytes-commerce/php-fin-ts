<?php

declare(strict_types=1);



namespace BytesCommerce\Syntax;

use BytesCommerce\Segment\AnonymousSegment;
use BytesCommerce\Segment\BaseDeg;
use BytesCommerce\Segment\BaseDescriptor;
use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\DegDescriptor;
use BytesCommerce\Segment\SegmentDescriptor;

abstract class Serializer
{
    /**
     * Escapes syntax characters (delimiters).
     * @param string $str The unescaped string.
     * @return string The escaped string in wire format.
     */
    public static function escape(string $str): string
    {
        return preg_replace("/([+:'?@])/", '?$1', $str);
    }

    /**
     * @param mixed|null $value A scalar (DE) value.
     * @param string $type The PHP type of this value. This should support exactly the values for which
     *     {@link ElementDescriptor::isScalarType()} returns true.
     * @return string The HBCI wire format representation of the value.
     */
    public static function serializeDataElement($value, string $type): string
    {
        if ($value === null) {
            return '';
        }

        if (in_array($type, ['int', 'integer', 'string'], true)) {
            // Convert UTF-8 (PHP's encoding) to ISO-8859-1 (FinTS wire format encoding)
            return static::escape(mb_convert_encoding(strval($value), 'ISO-8859-1', 'UTF-8'));
        }

        if ($type === 'float') {
            // Format with fixed 2 decimal places (there has to be some limit, and the specification does not specify
            // one), then trim zeros from the end.
            return rtrim(number_format($value, 2, ',', ''), '0');
        }

        if ($type === 'bool' || $type === 'boolean') {
            return $value ? 'J' : 'N';
        }

        throw new \RuntimeException('Unsupported type ' . $type);
    }

    /**
     * @param BaseDeg|null $baseDeg The data element group to be serialized. If null, all fields are implicitly null.
     * @param DegDescriptor $degDescriptor The descriptor for the DEG type.
     * @return string The HBCI wire format representation of the DEG.
     */
    public static function serializeDeg(?BaseDeg $baseDeg, DegDescriptor $degDescriptor): string
    {
        $serializedElements = Serializer::serializeElements($baseDeg, $degDescriptor);
        return implode(Delimiter::GROUP, self::flattenAndTrimEnd($serializedElements));
    }

    /**
     * @param BaseSegment $baseSegment The segment to be serialized.
     * @return string The HBCI wire format representation of the segment, in ISO-8859-1 encoding, terminated by the
     *     segment delimiter.
     */
    public static function serializeSegment(BaseSegment $baseSegment): string
    {
        if ($baseSegment instanceof AnonymousSegment) {
            throw new \InvalidArgumentException('Cannot serialize anonymous segments');
        }

        $serializedElements = self::serializeElements($baseSegment, $baseSegment->getDescriptor());
        return implode(Delimiter::ELEMENT, self::flattenAndTrimEnd($serializedElements)) . Delimiter::SEGMENT;
    }

    /**
     * @param BaseSegment[] $segments The segments to be serialized.
     * @return string The concatenated HBCI wire format representation of the segments.
     */
    public static function serializeSegments(array $segments): string
    {
        return implode('', array_map([self::class, 'serializeSegment'], $segments));
    }

    /**
     * @param BaseSegment|BaseDeg|null $obj An object to be serialized. If null, all fields are implicitly null.
     * @param BaseDescriptor $baseDescriptor The descriptor for the object to be serialized.
     * @return array A partial serialization of that object, namely a (possibly nested) array with all of its elements
     *     serialized independently, and at the right indices. In order to put subsequent elements in the right
     *     position, the returned array may contain emtpy strings as gaps/buffers in the middle (for subsequent elements
     *     in $obj) and/or at the end (for subsequent elements added by the caller for data following $obj).
     */
    private static function serializeElements($obj, BaseDescriptor $baseDescriptor): array
    {
        $isSegment = $baseDescriptor instanceof SegmentDescriptor;
        $serializedElements = [];
        $lastKey = array_key_last($baseDescriptor->elements);
        for ($index = 0; $index <= $lastKey; ++$index) {
            if (!array_key_exists($index, $baseDescriptor->elements)) {
                $serializedElements[$index] = '';
                continue;
            }

            $elementDescriptor = $baseDescriptor->elements[$index];
            $value = $obj === null ? null : $obj->{$elementDescriptor->field};
            if (array_key_exists($index, $serializedElements)) {
                throw new \AssertionError('Duplicate index ' . $index);
            }

            if ($elementDescriptor->repeated === 0) {
                $serializedElements[$index] = self::serializeElement($value, $elementDescriptor->type, $isSegment);
            } else {
                if ($value !== null && !is_array($value)) {
                    throw new \InvalidArgumentException(
                        sprintf('Expected array value for %s.%s, got: %s', $baseDescriptor->class, $elementDescriptor->field, $value));
                }

                if ($elementDescriptor->repeated === PHP_INT_MAX) {
                    // For an uncapped repeated field (with @Unlimited), it must be the very last field and we do not
                    // need to insert padding elements, so we only output its actual contents.
                    if ($index !== $lastKey) {
                        throw new \AssertionError(
                            sprintf('Expected unlimited field at %d to be the last one, but the last one is %s', $index, $lastKey)
                        );
                    }

                    $numOutputElements = count($value);
                } else {
                    // For a capped repeated field (with @Max), we need to output the specified number of elements, such
                    // that subsequent fields will be at the right place. If this is the last field, the trailing empty
                    // elements will be trimmed away again by flattenAndTrimEnd() later.
                    $numOutputElements = $elementDescriptor->repeated;
                }

                for ($repetition = 0; $repetition < $numOutputElements; ++$repetition) {
                    $serializedElements[$index + $repetition] = self::serializeElement(
                        $value === null || $repetition >= count($value) ? null : $value[$repetition],
                        $elementDescriptor->type, $isSegment);
                }

                $index += $numOutputElements - 1; // The outer loop will increment by 1 as well.
            }
        }

        return $serializedElements;
    }

    /**
     * @param mixed|null $value The value to be serialized.
     * @param string|\ReflectionClass $type The type of the value.
     * @param bool $fullySerialize If true, the result is always a string, complex values are imploded as a DEG.
     * @return string|array The serialized value. In case $type is a complex type and $fullySerialize is false, this
     *     returns a (possibly nested) array of strings.
     */
    private static function serializeElement($value, string|\ReflectionClass $type, bool $fullySerialize)
    {
        if (is_string($type)) { // Scalar value / DE
            return static::serializeDataElement($value, $type);
        }

        if ($type->getName() === Bin::class) {
            /* @var Bin|null $value */
            return $value === null ? '' : $value->toString();
        }

        if ($fullySerialize) {
            return static::serializeDeg($value, DegDescriptor::get($type->name));
        }

        return self::serializeElements($value, DegDescriptor::get($type->name));
    }

    /**
     * @param array $elements A possibly nested array of string values.
     * @return string[] A flat array with the same string values (using in-order tree traversal), but empty values
     *     removed from the end.
     */
    private static function flattenAndTrimEnd(array $elements): array
    {
        $result = [];
        $nonemptyLength = 0;
        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($elements)) as $element) {
            $result[] = $element;
            if ($element !== '') {
                $nonemptyLength = count($result);
            }
        }

        return array_slice($result, 0, $nonemptyLength);
    }
}

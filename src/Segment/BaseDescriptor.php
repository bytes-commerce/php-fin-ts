<?php

declare(strict_types=1);



namespace BytesCommerce\Segment;

use BytesCommerce\Syntax\Bin;

/**
 * Common functionality for segment/Deg descriptors.
 */
abstract class BaseDescriptor
{
    /** Example: "BytesCommerce\Segment\TAN\HITANSv6" (Segment) or "BytesCommerce\Common\Kik" (Deg) */
    public string $class;

    /** Example: 1 */
    public int $version = 1;

    /**
     * Descriptors for the elements inside the segment/Deg in the order of the wire format. The indices in this array
     * match the speficiation. In particular, the first index is 1 (not 0) and some indices may be missing if the
     * documentation does not specify it (anymore).
     * @var ElementDescriptor[]
     */
    public array $elements = [];

    /**
     * The last index that can be present in an exploded serialized segment/DEG. If one were to append a new field to
     * segment/DEG described by this descriptor, it would get index $maxIndex+1.
     * Usually $maxIndex==array_key_last($elements), but when the last element is repeated, then $maxIndex is larger.
     */
    public int $maxIndex;

    protected function __construct(\ReflectionClass $reflectionClass)
    {
        // Use reflection to map PHP class fields to elements in the segment/Deg.
        $nextIndex = 0;
        foreach (self::enumerateProperties($reflectionClass) as $property) {
            if ($nextIndex === null) {
                throw new \InvalidArgumentException(sprintf('Disallowed property %s after an @Unlimited field', $property));
            }

            $docComment = $property->getDocComment() ?: '';
            if ($this->getBoolAnnotation('Ignore', $docComment)) {
                continue; // Skip @Ignore-d propeties.
            }

            $index = $nextIndex;
            $descriptor = new ElementDescriptor();
            $descriptor->field = $property->getName();
            $maxCount = $this->getIntAnnotation('Max', $docComment);
            $unlimitedCount = $this->getBoolAnnotation('Unlimited', $docComment);
            if ($type = $this->getVarAnnotation($docComment)) {
                if (str_ends_with($type, '|null')) { // Nullable field
                    $descriptor->optional = true;
                    $type = substr($type, 0, -5);
                }

                if (str_ends_with($type, '[]')) { // Array/repeated field
                    $type = substr($type, 0, -2);
                    if ($unlimitedCount) {
                        $descriptor->repeated = PHP_INT_MAX;
                        // A repeated field of unlimited size cannot be followed by anything, because it would not be
                        // clear which of the following values still belong to the repeated field vs to the next field.
                        $nextIndex = null;
                    } elseif ($maxCount !== null) {
                        $descriptor->repeated = $maxCount;
                        // If there's another field value after this repeated field, then a serialized message will
                        // contain placeholders (i.e. empty field values separated by possibly hundreds of `+`) to fill
                        // up to the repeated field's maximum length, after which the next message continues at the next
                        // index.
                        $nextIndex += $maxCount;
                    } else {
                        throw new \InvalidArgumentException(
                            sprintf('Repeated property %s needs @Max(.) or (rarely) @Unlimited annotation', $property)
                        );
                    }
                } elseif ($maxCount !== null) {
                    throw new \InvalidArgumentException('@Max() annotation not recognized on single ' . $property);
                } elseif ($unlimitedCount) {
                    throw new \InvalidArgumentException('@Unlimited annotation not recognized on single ' . $property);
                } else {
                    ++$nextIndex; // Singular field, so the index advances by 1.
                }

                $descriptor->type = $this->resolveType($type, $property->getDeclaringClass());
            } elseif ($type = $property->getType()) {
                $descriptor->optional = $type->allowsNull();
                if ($type instanceof \ReflectionUnionType) {
                    throw new \InvalidArgumentException('Union type not supported for ' . $property);
                } elseif ($type->getName() === 'array') {
                    throw new \InvalidArgumentException('Array type must use @type annotation on ' . $property);
                } elseif ($type->isBuiltin()) {
                    $descriptor->type = $type->getName();
                } else {
                    try {
                        $descriptor->type = new \ReflectionClass($type->getName());
                    } catch (\ReflectionException $e) {
                        throw new \InvalidArgumentException(
                            sprintf('Cannot resolve type %s for %s', $type->getName(), $property), 0, $e);
                    }
                }

                ++$nextIndex; // Singular field, so the index advances by 1.
            } else {
                throw new \InvalidArgumentException('Need type on property ' . $property);
            }

            $this->elements[$index] = $descriptor;
        }

        if ($this->elements === []) {
            throw new \InvalidArgumentException('No fields found in ' . $reflectionClass->name);
        }

        ksort($this->elements); // Make sure elements are parsed in wire-format order.
        $this->maxIndex = $nextIndex === null ? PHP_INT_MAX : $nextIndex - 1;
    }

    /**
     * @param object $obj The object to be validated.
     * @throws \InvalidArgumentException If any of the fields in the given object is not valid according to the schema
     *     defined by this descriptor.
     */
    public function validateObject($obj): void
    {
        if (!is_a($obj, $this->class)) {
            throw new \InvalidArgumentException(sprintf('Expected %s, got ', $this->class) . gettype($obj));
        }

        foreach ($this->elements as $element) {
            $element->validateField($obj);
        }
    }

    /**
     * @param \ReflectionClass $reflectionClass The class name.
     * @return \Generator|\ReflectionProperty[] All non-static public properties of the given class and its parents, but
     *     with the parents' properties *first*.
     */
    private static function enumerateProperties(\ReflectionClass $reflectionClass): array|\Generator
    {
        if ($reflectionClass->getParentClass() !== false) {
            yield from self::enumerateProperties($reflectionClass->getParentClass());
        }

        foreach ($reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            if (!$reflectionProperty->isStatic() && $reflectionProperty->getDeclaringClass()->name === $reflectionClass->name) {
                yield $reflectionProperty;
            }
        }
    }

    /**
     * Looks for the annotation with the given name and extracts the content of the parentheses behind it. For instance,
     * when called with the name "Max" and a docComment that contains {@}Max(15), this would return "15".
     * @param string $name The name of the annotation.
     * @param string $docComment The documentation string of a PHP field.
     * @return string|null The content of the annotation, or null if absent.
     */
    private function getAnnotation(string $name, string $docComment): ?string
    {
        $ret = preg_match(sprintf('/@%s\((.*?)\)/', $name), $docComment, $match);
        if ($ret === false) {
            throw new \RuntimeException('preg_match failed on ' . $name);
        }

        return $ret === 1 ? $match[1] : null;
    }

    /**
     * Same as above, with integer parsing.
     * @param string $name The name of the annotation.
     * @param string $docComment The documentation string of a PHP field.
     * @return int|null The value of the annotation as an integer, or null if absent.
     */
    private function getIntAnnotation(string $name, string $docComment): ?int
    {
        $val = $this->getAnnotation($name, $docComment);
        if ($val === null) {
            return null;
        }

        if (!is_numeric($val)) {
            throw new \InvalidArgumentException(sprintf('Annotation %s has non-integer value %s', $name, $val));
        }

        return intval($val);
    }

    /**
     * @param string $name The name of the annotation.
     * @param string $docComment The documentation string of a PHP field.
     * @return bool Whether the annotation with the given name is present.
     */
    private function getBoolAnnotation(string $name, string $docComment): bool
    {
        return str_contains($docComment, sprintf('@%s ', $name))
            || str_contains($docComment, sprintf('@%s())', $name));
    }

    /**
     * Separate parser for the {@}var` annotation because it does not use parentheses.
     * @param string $docComment The documentation string of a PHP field.
     * @return string|null The value of the {@}var annotation, or null if absent.
     */
    private function getVarAnnotation(string $docComment): ?string
    {
        $ret = preg_match('/@var ([^\\s]+)/', $docComment, $match);
        if ($ret === false) {
            throw new \RuntimeException('preg_match failed for @var');
        }

        return $ret === 1 ? $match[1] : null;
    }

    /**
     * NOTE: This does *not* resolve `use` statements in the source file.
     * @param string $typeName A type name (PHP class name, fully qualified or not) or a scalar type name.
     * @param \ReflectionClass $reflectionClass The class where this type name was encountered, used for resolution of
     *     classes in the same package.
     * @return string|\ReflectionClass The class that the type name refers to, or the scalar type name as a string.
     */
    private function resolveType(string $typeName, \ReflectionClass $reflectionClass): \ReflectionClass|string
    {
        if (ElementDescriptor::isScalarType($typeName)) {
            return $typeName;
        }

        if ($typeName === 'Bin') {
            $typeName = Bin::class;
        } elseif (!str_contains($typeName, '\\')) {
            // Let's assume it's a relative type name, e.g. `X` mentioned in a file that starts with `namespace BytesCommerce\Y`
            // would become `\BytesCommerce\X\Y`.
            $typeName = $reflectionClass->getNamespaceName() . '\\' . $typeName;
        }

        try {
            return new \ReflectionClass($typeName);
        } catch (\ReflectionException $reflectionException) {
            throw new \RuntimeException($typeName . ' not found in context of ' . $reflectionClass->getName(), 0, $reflectionException);
        }
    }
}

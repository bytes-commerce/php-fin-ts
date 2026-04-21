<?php

declare(strict_types=1);



namespace BytesCommerce\Segment;

/**
 * Contains meta information about a data element group, i.e. anything that can be statically known about a sub-class of
 * {@link BaseDeg} through reflection.
 */
class DegDescriptor extends BaseDescriptor
{
    /** @var DegDescriptor[] */
    private static array $descriptors = [];

    /**
     * @param string $class The name of a sub-class of {@link BaseDeg}.
     * @return DegDescriptor The descriptor for the class.
     */
    public static function get(string $class): DegDescriptor
    {
        if (!array_key_exists($class, self::$descriptors)) {
            self::$descriptors[$class] = new DegDescriptor($class);
        }

        return self::$descriptors[$class];
    }

    /**
     * Please use the factory above.
     * @param string $class The name of a sub-class of {@link BaseDeg}.
     */
    protected function __construct(string $class)
    {
        $this->class = $class;
        try {
            $reflectionClass = new \ReflectionClass($class);
            if (!$reflectionClass->isSubclassOf(BaseDeg::class)) {
                throw new \InvalidArgumentException('Must inherit from BaseDeg: ' . $class);
            }

            parent::__construct($reflectionClass);

            // Check if the name ends in V2 or so, implicitly assume V1.
            if (preg_match('/^[A-Z]+[vV](\d+)$/', $reflectionClass->getShortName(), $match) === 1) {
                $this->version = intval($match[1]);
            }
        } catch (\ReflectionException $reflectionException) {
            throw new \RuntimeException($reflectionException, $reflectionException->getCode(), $reflectionException);
        }
    }
}

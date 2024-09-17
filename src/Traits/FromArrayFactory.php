<?php declare(strict_types=1);

namespace Torugo\Util\Traits;

use ReflectionObject;
use ReflectionProperty;

trait FromArrayFactory
{
    use EmptyValues;

    /**
     * Instantiates a class from a key=>value array.
     * The keys must be equal to the properties names.
     */
    public static function fromArray(array $data): self
    {
        $obj = new self;
        $props = self::getProperties($obj);

        foreach ($props as $prop) {
            $propName = $prop->getName();
            $propType = $prop->getType();

            $type = "unknown";
            $isNullable = false;
            $isReadOnly = $prop->isReadOnly();

            if ($propType != null && method_exists($propType, "getName")) {
                $type = $propType->getName();
                $isNullable = $propType->allowsNull();
            }

            if (array_key_exists($propName, $data)) {
                $obj->{$propName} = $data[$propName];
                continue;
            }

            // If the property is not present on $data and doesn't has a default value
            if (!$prop->isInitialized($obj) && !$prop->hasDefaultValue() && !$isReadOnly) {
                if ($isNullable) {
                    $obj->{$propName} = null;
                } else {
                    $obj->{$propName} = self::getEmptyValueForType($type);
                }
            }

        }

        if (self::hasMethodAfterFromArrayConstructor($obj)) {
            $obj->afterFromArrayConstructor();
        }

        return $obj;
    }

    /**
     * Get class properties as an array of ReflectionProperties
     * @param object $obj
     * @param bool $publicOnly Return only public properties (default false)
     * @return array ReflectionProperties[]
     */
    private static function getProperties(
        object $obj,
        bool $publicOnly = false
    ): array {
        $reflection = new ReflectionObject($obj);
        return $reflection->getProperties(
            $publicOnly ? ReflectionProperty::IS_PUBLIC : null
        );
    }

    /**
     * Checks if the class has the method 'afterFromArrayConstructor'
     * @param object $obj
     * @return bool
     */
    private static function hasMethodAfterFromArrayConstructor(object $obj): bool
    {
        try {
            $reflection = new ReflectionObject($obj);
            $method = $reflection->getMethod("afterFromArrayConstructor");
        } catch (\Throwable $th) {
            $method = null;
        }

        return $method != null;
    }

    /**
     * Returns all public properties as a key=>value pair array.
     * @return array
     */
    public function toArray(): array
    {
        $props = self::getProperties($this, true);

        $result = [];
        foreach ($props as $prop) {
            $result[$prop->getName()] = $prop->getValue($this);
        }

        return $result;
    }
}

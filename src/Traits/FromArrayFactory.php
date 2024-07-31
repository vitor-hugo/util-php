<?php declare(strict_types=1);

namespace Torugo\Util\Traits;

use ReflectionObject;

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
            if (!$prop->hasDefaultValue() && !$isReadOnly) {
                if ($isNullable) {
                    $obj->{$propName} = null;
                } else {
                    $obj->{$propName} = self::getEmptyValueForType($type);
                }
            }

        }

        return $obj;
    }

    /**
     * Get class properties as an array of ReflectionProperties
     * @param object $obj
     * @return array ReflectionProperties[]
     */
    private static function getProperties(object $obj): array
    {
        $reflection = new ReflectionObject($obj);
        return $reflection->getProperties();
    }
}

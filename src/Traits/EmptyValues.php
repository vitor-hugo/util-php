<?php declare(strict_types=1);

namespace Torugo\Util\Traits;

trait EmptyValues
{
    /**
     * Returns an empty value for a specific type
     * @param string $type
     * @return mixed Empty value
     */
    private static function getEmptyValueForType(string $type): mixed
    {
        $type = strtolower($type);

        return match ($type) {
            "array" => [],
            "boolean", "bool" => false,
            "double", "float" => 0.0,
            "integer", "int" => 0,
            "object" => new class {},
            "string" => "",
            default => null
        };
    }
}

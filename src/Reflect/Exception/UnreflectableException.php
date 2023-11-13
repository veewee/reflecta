<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect\Exception;

use VeeWee\Reflecta\Exception\RuntimeException;

class UnreflectableException extends RuntimeException
{
    public static function unknownProperty(string $className, string $property): self
    {
        return new self(sprintf('Unable to locate property %s::$%s', $className, $property));
    }

    public static function unknownClass(string $className): self
    {
        return new self(sprintf('Unable to locate class %s', $className));
    }

    public static function readonlyProperty(string $className, string $property): self
    {
        return new self(sprintf('Unable to write to readonly property %s::$%s', $className, $property));
    }
}

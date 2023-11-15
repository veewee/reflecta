<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect\Exception;

use Throwable;
use VeeWee\Reflecta\Exception\RuntimeException;

final class UnreflectableException extends RuntimeException
{
    public static function unknownProperty(string $className, string $property, ?Throwable $previous = null): self
    {
        return new self(
            sprintf('Unable to locate property %s::$%s.', $className, $property),
            0,
            $previous
        );
    }

    public static function unknownClass(string $className, ?Throwable $previous = null): self
    {
        return new self(
            sprintf('Unable to locate class %s.', $className),
            0,
            $previous
        );
    }

    public static function unwritableProperty(string $className, string $property, mixed $value, ?Throwable $previous = null): self
    {
        return new self(
            sprintf('Unable to write type %s to property %s::$%s.', get_debug_type($value), $className, $property),
            0,
            $previous
        );
    }

    public static function nonInstantiatable(string $className, Throwable $previous): self
    {
        return new self(
            sprintf('Unable to instantiate class %s.', $className),
            0,
            $previous
        );
    }
}

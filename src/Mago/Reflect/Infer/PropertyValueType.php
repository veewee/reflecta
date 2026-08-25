<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Mago\Reflect\Infer;

use Mago\Sdk\Analyzer\Argument;
use Mago\Sdk\Analyzer\Codebase;
use Mago\Sdk\Analyzer\Type;

final class PropertyValueType
{
    /**
     * Resolves the argument to a literal property name.
     */
    public static function inferName(?Argument $argument): ?string
    {
        return $argument?->type?->getLiteralString();
    }

    /**
     * Resolves the declared type of `$class::$$name`, falling back to the
     * declared `mixed` when the property is unknown or untyped.
     */
    public static function infer(Codebase $codebase, string $class, string $name): ?Type
    {
        $property = $codebase->getDeclaringProperty($class, '$' . $name)
            ?? $codebase->getProperty($class, '$' . $name);
        if ($property === null) {
            return null;
        }

        return ($property->type ?? $property->declaredType)?->type;
    }
}

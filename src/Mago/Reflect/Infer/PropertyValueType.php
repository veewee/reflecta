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
     * Resolves the declared type of `$class::$$name`.
     *
     * Goes through the same collection as `properties_get()` rather than a targeted
     * lookup: `ReflectedClass::property()` falls back to the parents, and a private
     * parent property is not reachable through `getProperty()` on the child.
     */
    public static function infer(Codebase $codebase, string $class, string $name): ?Type
    {
        $metadata = $codebase->getClassLike($class);
        if ($metadata === null) {
            return null;
        }

        return ClassProperties::collect($codebase, $metadata)[$name] ?? null;
    }
}

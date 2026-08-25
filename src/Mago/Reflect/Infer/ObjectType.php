<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Mago\Reflect\Infer;

use Mago\Sdk\Analyzer\Argument;
use Mago\Sdk\Analyzer\Type\NamedObjectType;

use function count;

final class ObjectType
{
    /**
     * Resolves the argument to a single, statically known class name.
     *
     * Anything wider - a union, `object`, a template parameter - has no single
     * class to reflect on, so inference is skipped and mago keeps the declared type.
     */
    public static function infer(?Argument $argument): ?string
    {
        $type = $argument?->type;
        if ($type === null || count($type->atomicTypes) !== 1) {
            return null;
        }

        $atomic = $type->atomicTypes[0];

        return $atomic instanceof NamedObjectType ? $atomic->name : null;
    }
}

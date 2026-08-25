<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Mago\Optic;

use Mago\Sdk\Analyzer\Invocation;
use Mago\Sdk\Analyzer\Type;
use Mago\Sdk\Analyzer\Type\NamedObjectType;

use function count;

/**
 * Chains the STAB parameters of a `compose()` call.
 *
 * For `compose(O<S, T, A1, B1>, O<A1, B1, A2, B2>, ...)` the composed optic keeps the
 * outer `(S, T)` of the first argument and the inner `(A, B)` of the last one.
 */
final class ComposedOpticType
{
    private const STAB_PARAMETER_COUNT = 4;

    /**
     * @param class-string $composed The concrete optic `compose()` folds into.
     */
    public static function infer(Invocation $invocation, string $composed): ?Type
    {
        $arguments = $invocation->arguments;
        if ($arguments === []) {
            return null;
        }

        $first = self::stabParameters($arguments[0]->type ?? null);
        $last = self::stabParameters($arguments[count($arguments) - 1]->type ?? null);
        if ($first === null || $last === null) {
            return null;
        }

        return Type::namedObject($composed, $first[0], $first[1], $last[2], $last[3]);
    }

    /**
     * @return null|list<Type>
     */
    private static function stabParameters(?Type $type): ?array
    {
        if ($type === null || count($type->atomicTypes) !== 1) {
            return null;
        }

        $atomic = $type->atomicTypes[0];
        if (!$atomic instanceof NamedObjectType) {
            return null;
        }

        $parameters = $atomic->parameters;

        return $parameters !== null && count($parameters) === self::STAB_PARAMETER_COUNT ? $parameters : null;
    }
}

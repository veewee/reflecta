<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use Closure;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedProperty;
use function Psl\Dict\intersect_by_key;
use function Psl\Iter\reduce_with_keys;

/**
 * @param T $object
 * @param array<string, mixed> $values
 * @param null|Closure(ReflectedProperty): bool $predicate
 *
 * @return T
 *@throws UnreflectableException
 *
 * @template T of object
 *
 */
function properties_set(object $object, array $values, Closure|null $predicate = null): object
{
    $properties = object_properties($object, $predicate);

    return reduce_with_keys(
        intersect_by_key($values, $properties),
        /**
         * @param T $object
         * @return T
         */
        static fn (object $object, string $name, mixed $value): object => property_set($object, $name, $value),
        $object
    );
}

<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use Closure;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\Property;
use function Psl\Dict\intersect_by_key;
use function Psl\Iter\reduce_with_keys;

/**
 * @throws UnreflectableException
 *
 * @template T of object
 *
 * @param T $object
 * @param array<string, mixed> $values
 * @param null|Closure(Property): bool $predicate
 *
 * @return T
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

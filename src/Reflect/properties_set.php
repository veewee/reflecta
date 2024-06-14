<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use Closure;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;
use VeeWee\Reflecta\Reflect\Type\ReflectedProperty;
use function Psl\Dict\diff_by_key;
use function Psl\Dict\filter;
use function Psl\Dict\intersect_by_key;
use function Psl\Dict\merge;
use function Psl\Iter\reduce_with_keys;

/**
 * @param T $object
 * @param array<string, mixed> $values
 * @param null|Closure(ReflectedProperty): bool $predicate
 *
 * @return T
 * @throws UnreflectableException
 *
 * @template T of object
 */
function properties_set(object $object, array $values, Closure|null $predicate = null): object
{
    $class = ReflectedClass::fromObject($object);

    if ($predicate) {
        $allProperties = $class->properties();
        $filteredProperties = filter($allProperties, $predicate);
        $unknownValues = $class->isDynamic() ? diff_by_key($values, $allProperties) : [];

        $values = merge($unknownValues, intersect_by_key($values, $filteredProperties));
    }

    return reduce_with_keys(
        $values,
        /**
         * @param T $object
         * @return T
         */
        static fn (object $object, string $name, mixed $value): object => property_set($object, $name, $value),
        $object
    );
}

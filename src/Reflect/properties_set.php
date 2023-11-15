<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use function Psl\Iter\reduce_with_keys;

/**
 * @throws UnreflectableException
 *
 * @template T of object
 *
 * @param T $object
 * @param array<string, mixed> $values
 *
 * @return T
 */
function properties_set(object $object, array $values): object
{
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

<?php

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Lens\Lens;

/**
 * @template U of object
 * @template C
 * @param string $propertyName
 * @return Lens<U, U, C, C>
 * @psalm-pure
 */
function property(string $propertyName): Lens {
    return new Lens(
        static fn (object $object): mixed => property_get($object, $propertyName),
        static fn (object $object, mixed $value): object => property_set($object, $propertyName, $value),
    );
}

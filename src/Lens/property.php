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
        /**
         * @param U $object
         * @return C
         *
         * @psalm-suppress MixedInferredReturnType, MixedReturnStatement
         */
        static fn (object $object): mixed => property_get($object, $propertyName),
        /**
         * @param U $object
         * @param C $value
         * @return U
         */
        static fn (object $object, mixed $value): object => property_set($object, $propertyName, $value),
    );
}

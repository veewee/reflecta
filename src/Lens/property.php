<?php

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Lens\Lens;

/**
 * @template S of object
 * @template A
 * @param string $propertyName
 * @return Lens<S, A>
 * @psalm-pure
 */
function property(string $propertyName): Lens {
    return new Lens(
        /**
         * @param S $subject
         * @return A
         *
         * @psalm-suppress MixedInferredReturnType, MixedReturnStatement
         */
        static fn (object $subject): mixed => property_get($subject, $propertyName),
        /**
         * @param S $subject
         * @param A $value
         * @return S
         */
        static fn (object $subject, mixed $value): object => property_set($subject, $propertyName, $value),
    );
}

<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

use function VeeWee\Reflecta\Reflect\property_get;
use function VeeWee\Reflecta\Reflect\property_set;

/**
 * @template S of object
 * @template A
 * @return Lens<S, A>
 * @psalm-pure
 */
function property(string $propertyName): Lens
{
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

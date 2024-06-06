<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

use function VeeWee\Reflecta\Reflect\property_get;
use function VeeWee\Reflecta\Reflect\property_set;

/**
 * @template S of object
 * @return Lens<S, mixed>
 * @psalm-pure
 */
function property(string $propertyName): Lens
{
    return new Lens(
        /**
         * @param S $subject
         */
        static fn (object $subject): mixed => property_get($subject, $propertyName),
        /**
         * @param S $subject
         * @return S
         */
        static fn (object $subject, mixed $value): object => property_set($subject, $propertyName, $value),
    );
}

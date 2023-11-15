<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

use function VeeWee\Reflecta\Reflect\properties_get;
use function VeeWee\Reflecta\Reflect\properties_set;

/**
 * @template S of object
 * @template A of array<string, mixed>
 *
 * @return Lens<S, A>
 * @psalm-pure
 */
function properties(): Lens
{
    /** @var Lens<S, A> */
    return new Lens(
        /**
         * @param S $subject
         * @return A
         *
         * @psalm-suppress InvalidReturnType, InvalidReturnStatement
         */
        static fn (object $subject): array => properties_get($subject),
        /**
         * @param S $subject
         * @param A $value
         * @return S
         */
        static fn (object $subject, array $value): object => properties_set($subject, $value),
    );
}

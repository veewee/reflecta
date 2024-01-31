<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

use Closure;
use VeeWee\Reflecta\Reflect\Type\Property;
use function VeeWee\Reflecta\Reflect\properties_get;
use function VeeWee\Reflecta\Reflect\properties_set;

/**
 * @template S of object
 * @template A of array<string, mixed>
 *
 * @param null|Closure(Property): bool $predicate
 *
 * @return Lens<S, A>
 * @psalm-pure
 */
function properties(Closure|null $predicate = null): Lens
{
    /** @var Lens<S, A> */
    return new Lens(
        /**
         * @param S $subject
         * @return A
         *
         * @psalm-suppress InvalidReturnType, InvalidReturnStatement
         */
        static fn (object $subject): array => properties_get($subject, $predicate),
        /**
         * @param S $subject
         * @param A $value
         * @return S
         */
        static fn (object $subject, array $value): object => properties_set($subject, $value, $predicate),
    );
}

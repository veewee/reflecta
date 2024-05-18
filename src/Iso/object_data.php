<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Iso;

use VeeWee\Reflecta\Lens\Lens;
use function VeeWee\Reflecta\Lens\properties;
use function VeeWee\Reflecta\Reflect\instantiate;

/**
 * @template S of object
 * @template A of array<string, mixed>
 *
 * @param class-string<S> $className
 * @param null|Lens<S, A> $accessor
 *
 * @return Iso<S, A>
 *
 * @psalm-pure
 */
function object_data(string $className, Lens $accessor = null): Iso
{
    /** @var Lens<S, A> $typedAccessor */
    $typedAccessor = $accessor ?? properties();

    return new Iso(
        /**
         * @param S $object
         * @return A
         */
        static fn (object $object): array => $typedAccessor->get($object),
        /**
         * @param A $properties
         * @return S
         */
        static fn (array $properties): object => $typedAccessor->set(
            instantiate($className),
            $properties
        ),
    );
}

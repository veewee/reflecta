<?php

namespace VeeWee\Reflecta\Iso;

use VeeWee\Reflecta\Lens\Lens;
use function VeeWee\Reflecta\Lens\properties;
use function VeeWee\Reflecta\Reflect\instantiate;

/**
 * @template S of object
 * @template A of array<string, mixed>
 * @param class-string<S> $className
 * @return Iso<S, A>
 * @psalm-pure
 */
function object_data(string $className): Iso {
    /** @var Lens<S, A> $propertiesLens */
    $propertiesLens = properties();

    return new Iso(
        /**
         * @param S $object
         * @return A
         */
        static fn (object $object): array => $propertiesLens->get($object),
        /**
         * @param A $properties
         * @return S
         */
        static fn (array $properties): object => $propertiesLens->set(
            instantiate($className),
            $properties
        ),
    );
}

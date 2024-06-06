<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Iso;

use VeeWee\Reflecta\Lens\Lens;
use function VeeWee\Reflecta\Lens\properties;
use function VeeWee\Reflecta\Reflect\instantiate;

/**
 * @template S of object
 * @param class-string<S> $className
 * @return Iso<S, array<string, mixed>>
 * @psalm-pure
 */
function object_data(string $className): Iso
{
    /** @var Lens<S, array<string, mixed>> $propertiesLens */
    $propertiesLens = properties();

    return new Iso(
        /**
         * @param S $object
         * @return array<string, mixed>
         */
        static fn (object $object): array => $propertiesLens->get($object),
        /**
         * @param array<string, mixed> $properties
         * @return S
         */
        static fn (array $properties): object => $propertiesLens->set(
            instantiate($className),
            $properties
        ),
    );
}

<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use ReflectionProperty;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;
use VeeWee\Reflecta\Reflect\Type\ReflectedProperty;
use function Psl\Dict\pull;

/**
 * @throws UnreflectableException
 * @return array<string, mixed>
 */
function properties_get(object $object): array
{
    return pull(
        ReflectedClass::fromObject($object)->properties(),
        static fn (ReflectedProperty $prop): mixed => $prop->apply(
            static fn (ReflectionProperty $property): mixed => $property->getValue($object)
        ),
        static fn (ReflectedProperty $prop): string => $prop->name()
    );
}

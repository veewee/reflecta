<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use ReflectionProperty;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;

/**
 * @throws UnreflectableException
 */
function property_get(object $object, string $name): mixed
{
    $property = ReflectedClass::fromObject($object)->property($name);

    return $property->apply(
        static fn (ReflectionProperty $property): mixed => $property->getValue($object)
    );
}

<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use ReflectionProperty;
use function get_parent_class;
use function property_exists;

function object_declared_property(object $object, string $name): ?ReflectionProperty
{
    $class = $object::class;
    do {
        // property_exists on a class name skips the dynamic properties of the instance.
        // Asking every parent finds the private properties that the child cannot see.
        if (property_exists($class, $name)) {
            return new ReflectionProperty($class, $name);
        }
    } while ($class = get_parent_class($class));

    return null;
}

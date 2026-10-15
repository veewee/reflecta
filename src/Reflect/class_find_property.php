<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use ReflectionProperty;
use function get_parent_class;
use function property_exists;

/**
 * @param class-string $className
 */
function class_find_property(string $className, string $name): ?ReflectionProperty
{
    $class = $className;
    do {
        // Asking every parent finds the private properties that the child cannot see.
        if (property_exists($class, $name)) {
            return new ReflectionProperty($class, $name);
        }
    } while ($class = get_parent_class($class));

    return null;
}

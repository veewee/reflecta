<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use ReflectionProperty;
use Throwable;
use function get_parent_class;
use function property_exists;

/**
 * Tells whether the object holds the property: a declared and initialised one, or a dynamic one. It never calls __get.
 * If PHP throws while checking, for example in a lazy object whose initializer fails, the property counts as present.
 */
function property_is_initialized(object $object, string $name): bool
{
    try {
        // Resolves the property in the same order as optional_property: the class itself, then a dynamic property, then the parents.
        $class = $object::class;
        if (!property_exists($class, $name) && property_exists($object, $name)) {
            return true;
        }

        do {
            if (property_exists($class, $name)) {
                return (new ReflectionProperty($class, $name))->isInitialized($object);
            }
        } while ($class = get_parent_class($class));

        return false;
    } catch (Throwable) {
        return true;
    }
}

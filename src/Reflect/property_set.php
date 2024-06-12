<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use ReflectionProperty;
use Throwable;
use VeeWee\Reflecta\Exception\CloneException;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;

/**
 * @throws UnreflectableException
 * @throws CloneException
 *
 * @template T of object
 * @param T $object
 * @psalm-param mixed $value
 * @return T
 */
function property_set(object $object, string $name, mixed $value): object
{
    try {
        /** @var T $new */
        $new = clone $object;
    } catch (Throwable $previous) {
        throw CloneException::impossibleToClone($object, $previous);
    }

    try {
        $property = ReflectedClass::fromObject($object)->property($name);
    } catch (UnreflectableException $e) {
        // In case the property is unknown, try to set a dynamic property.
        if (object_is_dynamic($new)) {
            $new->{$name} = $value;

            return $new;
        }

        throw $e;
    }

    try {
        $property->apply(
            static fn (ReflectionProperty $reflectionProperty) => $reflectionProperty->setValue($new, $value)
        );
    } catch (Throwable $previous) {
        throw UnreflectableException::unwritableProperty(get_debug_type($object), $name, $value, $previous);
    }

    return $new;
}

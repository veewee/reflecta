<?php declare(strict_types=1);
namespace VeeWee\Reflecta\Reflect;

use ReflectionObject;
use Throwable;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;

/**
 * @psalm-internal VeeWee\Reflecta
 *
 *
 * @throws UnreflectableException
 */
function reflect_object(object $object): ReflectionObject
{
    try {
        return new ReflectionObject($object);
    } catch (Throwable $previous) {
        throw UnreflectableException::unknownClass(get_debug_type($object), $previous);
    }
}

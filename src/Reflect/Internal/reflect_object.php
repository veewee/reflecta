<?php declare(strict_types=1);
namespace VeeWee\Reflecta\Reflect;

use ReflectionObject;

/**
 * @psalm-internal VeeWee\Reflecta
 */
function reflect_object(object $object): ReflectionObject
{
    return new ReflectionObject($object);
}

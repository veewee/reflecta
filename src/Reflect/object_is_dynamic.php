<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;

/**
 * @throws UnreflectableException
 */
function object_is_dynamic(object $object): bool
{
    return ReflectedClass::fromObject($object)->isDynamic();
}

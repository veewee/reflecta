<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;

/**
 * @throws UnreflectableException
 *
 * @param class-string $className
 */
function class_is_dynamic(string $className): bool
{
    return ReflectedClass::fromFullyQualifiedClassName($className)->isDynamic();
}

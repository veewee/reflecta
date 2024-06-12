<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;

/**
 * @template T of object
 * @param class-string<T> $className
 * @return T
 *
 * @throws UnreflectableException
 */
function instantiate(string $className): mixed
{
    /** @var T */
    return ReflectedClass::fromFullyQualifiedClassName($className)->instantiate();
}

<?php declare(strict_types=1);
namespace VeeWee\Reflecta\Reflect;

use ReflectionClass;
use Throwable;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;

/**
 * @psalm-internal VeeWee\Reflecta
 *
 * @template T
 * @param class-string<T> $className
 * @return ReflectionClass<T>
 *
 * @throws UnreflectableException
 */
function reflect_class(string $className): ReflectionClass
{
    try {
        return new ReflectionClass($className);
    } catch (Throwable $previous) {
        throw UnreflectableException::unknownClass($className, $previous);
    }
}

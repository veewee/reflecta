<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;

/**
 * @template T extends object
 *
 * @param class-string $className
 * @param class-string<T>|null $attributeClassName
 * @return (T is null ? list<object> : list<T>)
 *
 * @throws UnreflectableException
 */
function class_attributes(string $className, ?string $attributeClassName = null): array
{
    return ReflectedClass::fromFullyQualifiedClassName($className)->attributes($attributeClassName);
}

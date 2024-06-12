<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;

/**
 * @template T extends object
 *
 * @param class-string<T>|null $attributeClassName
 * @return (T is null ? list<object> : list<T>)
 *
 * @throws UnreflectableException
 */
function object_attributes(object $object, ?string $attributeClassName = null): array
{
    return ReflectedClass::fromObject($object)->attributes($attributeClassName);
}

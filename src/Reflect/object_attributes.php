<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use function VeeWee\Reflecta\Reflect\Internal\reflect_class_attributes;

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
    return reflect_class_attributes($object, $attributeClassName);
}

<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use ReflectionAttribute;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;

/**
 * @throws UnreflectableException
 *
 * @param class-string $attributeClassName
 */
function object_has_attribute(object $object, string $attributeClassName): bool
{
    $propertyInfo = reflect_object($object);

    return (bool) $propertyInfo->getAttributes($attributeClassName, ReflectionAttribute::IS_INSTANCEOF);
}

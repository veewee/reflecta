<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use ReflectionAttribute;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;

/**
 * @throws UnreflectableException
 *
 * @param class-string $className
 * @param class-string $attributeClassName
 */
function class_has_attribute(string $className, string $attributeClassName): bool
{
    $propertyInfo = reflect_class($className);

    return (bool) $propertyInfo->getAttributes($attributeClassName, ReflectionAttribute::IS_INSTANCEOF);
}

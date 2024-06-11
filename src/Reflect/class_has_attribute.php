<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;

/**
 * @throws UnreflectableException
 *
 * @param class-string $className
 * @param class-string $attributeClassName
 */
function class_has_attribute(string $className, string $attributeClassName): bool
{
    return ReflectedClass::fromFullyQualifiedClassName($className)->hasAttributeOfType($attributeClassName);
}

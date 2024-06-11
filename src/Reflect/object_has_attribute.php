<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;

/**
 * @throws UnreflectableException
 *
 * @param class-string $attributeClassName
 */
function object_has_attribute(object $object, string $attributeClassName): bool
{
    return ReflectedClass::fromObject($object)->hasAttributeOfType($attributeClassName);
}

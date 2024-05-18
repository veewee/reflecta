<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect\Predicate;

use Closure;
use ReflectionAttribute;
use VeeWee\Reflecta\Reflect\Type\ClassInfo;
use function VeeWee\Reflecta\Reflect\Internal\reflect_class;

/**
 * @param class-string $attributeClassName
 * @return Closure(ClassInfo): bool
 */
function class_has_attribute_of_type(string $attributeClassName): Closure
{
    return static function (ClassInfo $class) use ($attributeClassName) : bool {
        $reflection = reflect_class($class->fullName());

        return (bool) $reflection->getAttributes($attributeClassName, ReflectionAttribute::IS_INSTANCEOF);
    };
}

<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use Closure;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;
use VeeWee\Reflecta\Reflect\Type\ReflectedProperty;

/**
 * @param class-string $className
 * @param null|Closure(ReflectedProperty): bool $predicate
 *
 * @return array<string, ReflectedProperty>
 *@throws UnreflectableException
 */
function class_properties(string $className, Closure|null $predicate = null): array
{
    return ReflectedClass::fromFullyQualifiedClassName($className)->properties($predicate);
}

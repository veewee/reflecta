<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use Closure;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;
use VeeWee\Reflecta\Reflect\Type\ReflectedProperty;

/**
 * @param null|Closure(ReflectedProperty): bool $predicate
 *
 * @return array<string, ReflectedProperty>
 *@throws UnreflectableException
 */
function object_properties(object $object, Closure|null $predicate = null): array
{
    return ReflectedClass::fromObject($object)->properties($predicate);
}

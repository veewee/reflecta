<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use Closure;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;
use VeeWee\Reflecta\Reflect\Type\ReflectedProperty;
use function Psl\Dict\pull;

/**
 * @param null|Closure(ReflectedProperty): bool $predicate
 *
 * @return array<string, mixed>
 * @throws UnreflectableException
 */
function properties_get(object $object, Closure|null $predicate = null): array
{
    return pull(
        ReflectedClass::fromObject($object)->properties($predicate),
        static fn (ReflectedProperty $property): mixed => property_get($object, $property->name()),
        static fn (ReflectedProperty $property): string => $property->name()
    );
}

<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use Closure;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\Property;
use function Psl\Dict\pull;

/**
 * @param null|Closure(Property): bool $predicate
 *
 * @throws UnreflectableException
 * @return array<string, mixed>
 */
function properties_get(object $object, Closure|null $predicate = null): array
{
    $properties = object_properties($object, $predicate);

    return pull(
        $properties,
        static fn (Property $property): mixed => property_get($object, $property->name()),
        static fn (Property $property): string => $property->name()
    );
}

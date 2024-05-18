<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use Closure;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\Property;
use function VeeWee\Reflecta\Reflect\Internal\reflect_properties;

/**
 * @param null|Closure(Property): bool $predicate
 *
 * @throws UnreflectableException
 * @return array<string, Property>
 */
function object_properties(object $object, Closure|null $predicate = null): array
{
    return reflect_properties($object, $predicate);
}

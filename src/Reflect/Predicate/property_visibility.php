<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect\Predicate;

use Closure;
use VeeWee\Reflecta\Reflect\Type\Property;
use VeeWee\Reflecta\Reflect\Type\Visibility;

/**
 * @return Closure(Property): bool
 */
function property_visibility(Visibility $visibility): Closure
{
    return static fn (Property $property): bool => $property->visibility() === $visibility;
}

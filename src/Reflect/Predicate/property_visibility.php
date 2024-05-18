<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect\Predicate;

use Closure;
use VeeWee\Reflecta\Reflect\Type\ReflectedProperty;
use VeeWee\Reflecta\Reflect\Type\Visibility;

/**
 * @return Closure(ReflectedProperty): bool
 */
function property_visibility(Visibility $visibility): Closure
{
    return static fn (ReflectedProperty $property): bool => $property->visibility() === $visibility;
}

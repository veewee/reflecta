<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect\Internal;

use Closure;
use ReflectionProperty;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\Property;
use function Psl\Dict\filter;
use function Psl\Dict\pull;

/**
 * @psalm-internal VeeWee\Reflecta
 *
 * @param object|class-string $objectOrClassName
 * @param null|Closure(Property): bool $predicate
 *
 * @throws UnreflectableException
 * @return array<string, Property>
 */
function reflect_properties(mixed $objectOrClassName, Closure|null $predicate = null): array
{
    $reflection = is_string($objectOrClassName) ? reflect_class($objectOrClassName) : reflect_object($objectOrClassName);

    $properties = pull(
        $reflection->getProperties(),
        static fn (ReflectionProperty $reflectionProperty): Property => new Property($reflectionProperty),
        static fn (ReflectionProperty $reflectionProperty): string => $reflectionProperty->name
    );

    if ($predicate !== null) {
        return filter($properties, $predicate);
    }

    return $properties;
}

<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use Closure;
use ReflectionProperty;
use Throwable;
use VeeWee\Reflecta\Exception\CloneException;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;
use VeeWee\Reflecta\Reflect\Type\ReflectedProperty;
use function Psl\Dict\diff_by_key;
use function Psl\Dict\filter;
use function Psl\Dict\intersect_by_key;
use function Psl\Dict\merge;

/**
 * @param T $object
 * @param array<string, mixed> $values
 * @param null|Closure(ReflectedProperty): bool $predicate
 *
 * @return T
 * @throws UnreflectableException
 *
 * @template T of object
 */
function properties_set(object $object, array $values, Closure|null $predicate = null): object
{
    $class = ReflectedClass::fromObject($object);

    if ($predicate) {
        $allProperties = $class->properties();
        $filteredProperties = filter($allProperties, $predicate);
        $unknownValues = $class->isDynamic() ? diff_by_key($values, $allProperties) : [];

        $values = merge($unknownValues, intersect_by_key($values, $filteredProperties));
    }

    if (!$values) {
        return $object;
    }

    try {
        /** @var T $new */
        $new = clone $object;
    } catch (Throwable $previous) {
        throw CloneException::impossibleToClone($object, $previous);
    }

    $isDynamic = $class->isDynamic();

    /** @var mixed $value */
    foreach ($values as $name => $value) {
        try {
            $class->property($name)->apply(
                static fn (ReflectionProperty $reflectionProperty) => $reflectionProperty->setValue($new, $value)
            );
        } catch (UnreflectableException $e) {
            if ($isDynamic) {
                $new->{$name} = $value;
            } else {
                throw $e;
            }
        }
    }

    return $new;
}

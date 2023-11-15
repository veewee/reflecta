<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use ReflectionProperty;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use function Psl\Dict\pull;

/**
 * @throws UnreflectableException
 * @return array<string, mixed>
 */
function properties_get(object $object): array
{

    $propertyInfo = reflect_object($object);

    return pull(
        $propertyInfo->getProperties(),
        static fn (ReflectionProperty $reflectionProperty): mixed => $reflectionProperty->getValue($object),
        static fn (ReflectionProperty $reflectionProperty): string => $reflectionProperty->name
    );
}

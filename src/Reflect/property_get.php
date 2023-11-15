<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;

/**
 * @throws UnreflectableException
 */
function property_get(object $object, string $name): mixed
{

    $propertyInfo = reflect_property($object, $name);

    return $propertyInfo->getValue($object);
}

<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ClassInfo;
use function VeeWee\Reflecta\Reflect\Internal\reflect_object;

/**
 * @throws UnreflectableException
 */
function object_info(object $object): ClassInfo
{
    $reflection = reflect_object($object);

    return new ClassInfo($reflection);
}

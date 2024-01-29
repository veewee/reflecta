<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ClassInfo;
use function VeeWee\Reflecta\Reflect\Internal\reflect_class;

/**
 * @param class-string $className
 *
 * @throws UnreflectableException
 */
function class_info(string $className): ClassInfo
{
    $reflection = reflect_class($className);

    return new ClassInfo($reflection);
}

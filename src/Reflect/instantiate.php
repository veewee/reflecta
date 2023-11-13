<?php

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Exception\UnreflectableException;

/**
 * @template T
 * @param class-string<T> $object
 * @return T
 */
function instantiate(string $className): mixed {
    $classInfo = new \ReflectionClass($className);

    return $classInfo->newInstanceWithoutConstructor();
}

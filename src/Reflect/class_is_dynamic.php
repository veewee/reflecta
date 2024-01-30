<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use AllowDynamicProperties;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use const PHP_VERSION_ID;

/**
 * @throws UnreflectableException
 *
 * @param class-string $className
 */
function class_is_dynamic(string $className): bool
{
    // Dynamic props is a 80200 feature.
    // IN previous versions, all objects are dynamic (without any warning).
    if (PHP_VERSION_ID < 80200) {
        return true;
    }

    return class_has_attribute($className, AllowDynamicProperties::class);
}

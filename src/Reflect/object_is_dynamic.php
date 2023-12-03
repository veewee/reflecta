<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use AllowDynamicProperties;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use const PHP_VERSION_ID;

/**
 * @throws UnreflectableException
 */
function object_is_dynamic(object $object): bool
{
    // Dynamic props is a 80200 feature.
    // IN previous versions, all objects are dynamic (without any warning).
    if (PHP_VERSION_ID < 80200) {
        return true;
    }

    return object_has_attribute($object, AllowDynamicProperties::class);
}

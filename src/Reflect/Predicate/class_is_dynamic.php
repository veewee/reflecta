<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect\Predicate;

use AllowDynamicProperties;
use Closure;
use VeeWee\Reflecta\Reflect\Type\ClassInfo;

/**
 * @return Closure(ClassInfo): bool
 */
function class_is_dynamic(): Closure
{
    return static function (ClassInfo $class) : bool {
        // Dynamic props is a 80200 feature.
        // IN previous versions, all objects are dynamic (without any warning).
        if (PHP_VERSION_ID < 80200) {
            return true;
        }

        return $class->check(
            class_has_attribute_of_type(AllowDynamicProperties::class)
        );
    };
}

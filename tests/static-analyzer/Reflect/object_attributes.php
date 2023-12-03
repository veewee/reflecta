<?php declare(strict_types=1);

namespace VeeWee\Reflecta\SaTests\Reflect;

use AllowDynamicProperties;
use stdClass;
use function VeeWee\Reflecta\Reflect\object_attributes;

/**
 * @return list<object>
 */
function test_get_all(): array
{
    $std = new stdClass();

    return object_attributes($std);
}

/**
 * @return list<AllowDynamicProperties>
 */
function test_get_specific(): array
{
    $std = new stdClass();

    return object_attributes($std, AllowDynamicProperties::class);
}

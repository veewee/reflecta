<?php

namespace VeeWee\Reflecta\SaTests\Reflect;

use VeeWee\Reflecta\SaTests\Fixtures\X;
use function VeeWee\Reflecta\Reflect\property_set;

function test_set_valid_prop_value_type(): X {
    $z = 'z';
    $x = new X();
    $x->z = 123;

    return property_set($x, $z, 456);
}

/**
 * @psalm-suppress InvalidScalarArgument
 */
function test_set_invalid_prop_value_type(): X {
    $z = 'z';
    $x = new X();
    $x->z = 123;

    return property_set($x, $z, 'nope');
}

function test_return_type_on_templated_object(): object {
    $curried = fn(string $path): \Closure => static fn (object $object, mixed $value): mixed => property_set($object, $path, $value);
    $z = 'z';
    $x = new X();

    return $curried($z)($x, 456);
}

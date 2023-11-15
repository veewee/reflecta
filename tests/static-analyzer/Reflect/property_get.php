<?php

namespace VeeWee\Reflecta\SaTests\Reflect;

use VeeWee\Reflecta\SaTests\Fixtures\X;
use function VeeWee\Reflecta\Reflect\property_get;

function test_get_prop_return_type(): ?int {
    $z = 'z';
    $x = new X();
    $x->z = 123;

    return property_get($x, $z);
}

function test_get_mixed_return_type_on_templated_object(): mixed {
    $curried = fn(string $path): \Closure => static fn (object $object): mixed => property_get($object, $path);
    $z = 'z';
    $x = new X();

    return $curried($z)($x);
}

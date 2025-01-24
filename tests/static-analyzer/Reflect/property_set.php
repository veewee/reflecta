<?php declare(strict_types=1);

namespace VeeWee\Reflecta\SaTests\Reflect;

use Closure;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\property_set;

function test_set_valid_prop_value_type(): X
{
    $z = 'z';
    $x = new X();
    $x->z = 123;

    return property_set($x, $z, 456);
}

/**
 * @psalm-suppress InvalidScalarArgument
 */
function test_set_invalid_prop_value_type(): X
{
    $z = 'z';
    $x = new X();
    $x->z = 123;

    return property_set($x, $z, 'nope');
}

/**
 * @psalm-suppress UndefinedPropertyAssignment
 */
function test_assigning_unknown_property(): X
{
    $unknown = 'unknown';
    $x = new X();

    return property_set($x, $unknown, 'nope');
}

function test_return_type_on_templated_object(): object
{
    $curried = static fn (string $path): Closure => static fn (object $object, mixed $value): mixed => property_set($object, $path, $value);
    $z = 'z';
    $x = new X();

    return $curried($z)($x, 456);
}

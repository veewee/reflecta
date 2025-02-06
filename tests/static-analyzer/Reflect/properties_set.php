<?php declare(strict_types=1);

namespace VeeWee\Reflecta\SaTests\Reflect;

use VeeWee\Reflecta\Reflect\Type\Visibility;
use VeeWee\Reflecta\TestFixtures\Dynamic;
use VeeWee\Reflecta\TestFixtures\MultipleProperties;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\Predicate\property_visibility;
use function VeeWee\Reflecta\Reflect\properties_set;

function test_set_valid_prop_value_type(): X
{
    $x = new X();
    $x->z = 123;

    return properties_set($x, ['z' => 456]);
}

function test_set_valid_prop_value_type_with_predicate(): X
{
    $x = new X();
    $x->z = 123;

    return properties_set($x, ['z' => 456], property_visibility(Visibility::Private));
}

function test_set_partial_props(): MultipleProperties
{
    $x = new MultipleProperties();
    $x->a = '';
    $x->b = '';

    return properties_set($x, ['c' => 'foo']);
}

/**
 * @psalm-suppress InvalidScalarArgument
 */
function test_set_invalid_prop_value_type(): X
{
    $x = new X();
    $x->z = 123;

    return properties_set($x, ['z' => 'nope']);
}

/**
 * @psalm-suppress InvalidArgument
 */
function test_assigning_unknown_property(): X
{
    $x = new X();

    return properties_set($x, ['unknown' => 'nope']);
}

function test_set_new_prop_on_dynamic_class(): Dynamic
{
    $x = new Dynamic();
    $x->x = 'string';

    return properties_set($x, ['foo' => 'bar']);
}

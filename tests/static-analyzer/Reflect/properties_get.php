<?php declare(strict_types=1);

namespace VeeWee\Reflecta\SaTests\Reflect;

use Closure;
use VeeWee\Reflecta\Reflect\Type\Visibility;
use VeeWee\Reflecta\TestFixtures\Dynamic;
use VeeWee\Reflecta\TestFixtures\MultipleProperties;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\Predicate\property_visibility;
use function VeeWee\Reflecta\Reflect\properties_get;

/**
 * @return array{z: int|null}
 */
function test_get_prop_return_type(): array
{
    $x = new X();
    $x->z = 123;

    return properties_get($x);
}

/**
 * @return array{z ?: int|null}
 */
function test_get_optional_prop_return_type(): array
{
    $x = new X();
    $x->z = 123;

    return properties_get($x, property_visibility(Visibility::Private));
}

/**
 * @return array{a: string, b: string, c: string}
 */
function test_get_multi_props_return_type(): array
{
    $x = new MultipleProperties();

    return properties_get($x);
}

/**
 * @return array{a ?: string, b ?: string, c ?: string}
 */
function test_get_optional_multi_props_return_type(): array
{
    $x = new MultipleProperties();

    return properties_get($x, property_visibility(Visibility::Private));
}

/**
 * @return array{x: string, ...<array-key, mixed>}
 */
function test_get_dynamic_props_return_type(): array
{
    $x = new Dynamic();

    return properties_get($x);
}

/**
 * @return array{x ?: string, ...<array-key, mixed>}
 */
function test_get_optional_dynamic_props_return_type(): array
{
    $x = new Dynamic();

    return properties_get($x, property_visibility(Visibility::Private));
}

function test_get_mixed_return_type_on_templated_object(): array
{
    $curried = static fn (): Closure => static fn (object $object): array => properties_get($object);
    $x = new X();

    return $curried()($x);
}

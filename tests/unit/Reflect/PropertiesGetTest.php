<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Reflect\Type\ReflectedProperty;
use VeeWee\Reflecta\TestFixtures\Dynamic;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\properties_get;

final class PropertiesGetTest extends TestCase
{

    public function test_it_can_get_properties(): void
    {
        $x = new X();
        $x->z = 123;

        $actual = properties_get($x);

        static::assertSame(['z' => 123], $actual);
    }

    public function test_it_can_get_with_predicate_filter(): void
    {
        $x = new Dynamic();
        $x->x = '123';
        $x->y = '123';
        $actual = properties_get($x, static fn (ReflectedProperty $property): bool => $property->isDefault());

        static::assertSame(['x' => '123'], $actual);
    }
}

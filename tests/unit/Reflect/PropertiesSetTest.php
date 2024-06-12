<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use PHPUnit\Framework\TestCase;
use stdClass;
use VeeWee\Reflecta\Reflect\Type\ReflectedProperty;
use VeeWee\Reflecta\TestFixtures\Dynamic;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\properties_set;

final class PropertiesSetTest extends TestCase
{

    public function test_it_can_set_properties(): void
    {
        $x = new X();
        $actual = properties_set($x, ['z' => 123]);

        static::assertNotSame($x, $actual);
        static::assertInstanceOf(X::class, $actual);
        static::assertSame($actual->z, 123);
    }

    public function test_it_can_set_with_predicate_filter(): void
    {
        $x = new Dynamic();
        $x->x = '123';
        $x->y = '123';
        $actual = properties_set($x, ['x' => '456', 'y' => '456'], static fn (ReflectedProperty $property): bool => $property->isDefault());

        static::assertNotSame($x, $actual);
        static::assertInstanceOf(Dynamic::class, $actual);
        static::assertSame($actual->x, '456');
        static::assertSame($actual->y, '123');
    }

    public function test_it_can_hydrate_new_props_on_std_class(): void
    {
        $x = new stdClass();
        $x->foo = 'foo';

        $actual = properties_set($x, ['bar' => 'bar']);

        static::assertNotSame($x, $actual);
        static::assertInstanceOf(stdClass::class, $actual);
        static::assertSame($actual->foo, 'foo');
        static::assertSame($actual->bar, 'bar');
    }

    public function test_it_can_hydrate_new_props_on_std_class_with_predicate(): void
    {
        $x = new stdClass();
        $x->foo = 'foo';

        $actual = properties_set(
            $x,
            [
                'foo' => 'baz',
                'bar' => 'bar',
            ],
            static fn (ReflectedProperty $property): bool => false
        );

        static::assertNotSame($x, $actual);
        static::assertInstanceOf(stdClass::class, $actual);
        static::assertSame($actual->foo, 'foo');
        static::assertSame($actual->bar, 'bar');
    }
}

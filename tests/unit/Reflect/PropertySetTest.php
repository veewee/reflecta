<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Exception\CloneException;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\TestFixtures\ReadonlyX;
use VeeWee\Reflecta\TestFixtures\Unclonable;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\instantiate;
use function VeeWee\Reflecta\Reflect\property_set;

final class PropertySetTest extends TestCase
{

    public function test_it_can_set_property(): void
    {
        $x = new X();
        $x->z = 123;

        $actual = property_set($x, 'z', 345);

        static::assertNotSame($x, $actual);
        static::assertInstanceOf(X::class, $actual);
        static::assertSame(345, $actual->z);
    }


    public function test_it_errors_on_unclonable(): void
    {
        $this->expectException(CloneException::class);

        $x = new Unclonable();
        property_set($x, 'z', 345);
    }


    public function test_it_errors_on_initialized_readonly(): void
    {
        $this->expectException(UnreflectableException::class);

        $x = new ReadonlyX(123);
        property_set($x, 'z', 345);
    }


    public function test_it_can_deal_with_uninitialized_readonly(): void
    {
        $x = instantiate(ReadonlyX::class);
        $actual = property_set($x, 'z', 345);

        static::assertNotSame($x, $actual);
        static::assertInstanceOf(ReadonlyX::class, $actual);
        static::assertSame(345, $actual->z);
    }
}

<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\TestFixtures\ReadonlyX;
use VeeWee\Reflecta\TestFixtures\Unclonable;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\instantiate;
use function VeeWee\Reflecta\Reflect\property_set;

class PropertySetTest extends TestCase
{
    /** @test */
    public function it_can_set_property(): void
    {
        $x = new X();
        $x->z = 123;

        $actual = property_set($x, 'z', 345);

        self::assertNotSame($x, $actual);
        self::assertInstanceOf(X::class, $actual);
        self::assertSame(345, $actual->z);
    }

    /** @test */
    public function it_errors_on_unclonable(): void
    {
        $this->expectException(Unclonable::class);

        $x = new Unclonable();
        property_set($x, 'z', 345);
    }

    /** @test */
    public function it_errors_on_initialized_readonly(): void
    {
        $this->expectException(UnreflectableException::class);

        $x = new ReadonlyX(123);
        property_set($x, 'z', 345);
    }

    /** @test */
    public function it_can_deal_with_uninitialized_readonly(): void
    {
        $x = instantiate(ReadonlyX::class);
        $actual = property_set($x, 'z', 345);

        self::assertNotSame($x, $actual);
        self::assertInstanceOf(ReadonlyX::class, $actual);
        self::assertSame(345, $actual->z);
    }
}

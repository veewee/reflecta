<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\property_get;

final class PropertyGetTest extends TestCase
{

    public function test_it_can_get_property(): void
    {
        $x = new X();
        $x->z = 123;

        $actual = property_get($x, 'z');

        static::assertSame(123, $actual);
    }

    public function test_it_can_fail_getting_property_value(): void
    {
        $x = new class() {
            /**
             * This property is not initialize yet...
             */
            private string $x;
        };

        $this->expectException(UnreflectableException::class);
        $this->expectExceptionMessage('Unable to read property '.$x::class.'::$x.');
        property_get($x, 'x');
    }
}

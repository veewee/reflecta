<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\property_get;

class PropertyGetTest extends TestCase
{
    /** @test */
    public function it_can_get_property(): void
    {
        $x = new X();
        $x->z = 123;

        $actual = property_get($x, 'z');

        self::assertSame(123, $actual);
    }
}

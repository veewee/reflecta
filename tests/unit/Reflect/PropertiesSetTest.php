<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\properties_set;

class PropertiesSetTest extends TestCase
{
    /** @test */
    public function it_can_get_properties(): void
    {
        $x = new X();
        $actual = properties_set($x, ['z' => 123]);

        self::assertNotSame($x, $actual);
        self::assertInstanceOf(X::class, $actual);
        self::assertSame($actual->z, 123);
    }
}

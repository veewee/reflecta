<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use PHPUnit\Framework\TestCase;
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
}

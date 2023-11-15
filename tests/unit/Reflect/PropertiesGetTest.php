<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\properties_get;

class PropertiesGetTest extends TestCase
{
    /** @test */
    public function it_can_get_properties(): void
    {
        $x = new X();
        $x->z = 123;

        $actual = properties_get($x);

        self::assertSame(['z' => 123], $actual);
    }
}

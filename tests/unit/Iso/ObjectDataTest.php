<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Iso;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Reflect\Type\Visibility;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Iso\object_data;
use function VeeWee\Reflecta\Lens\properties;
use function VeeWee\Reflecta\Reflect\Predicate\property_visibility;

final class ObjectDataTest extends TestCase
{

    public function test_it_can_work_with_object_data(): void
    {
        $iso = object_data(X::class);

        $expectedData = ['z' => 100];
        $expectedInstance = new X();
        $expectedInstance->z = 100;

        $instance = $iso->from($expectedData);
        $actualData = $iso->to($instance);

        static::assertEquals($expectedInstance, $instance);
        static::assertSame($expectedData, $actualData);
    }

    public function test_it_can_use_alternate_lens(): void
    {
        $x = new class {
            public int $z = 100;
            private int $y = 200;
        };

        $iso = object_data(X::class, properties(property_visibility(Visibility::Public)));

        $expectedData = ['z' => 100];
        $expectedInstance = new X();
        $expectedInstance->z = 100;

        $instance = $iso->from($expectedData);
        $actualData = $iso->to($instance);

        static::assertEquals($expectedInstance, $instance);
        static::assertSame($expectedData, $actualData);

        $actualSkippedPrivate = $iso->from(['z' => 300, 'y' => 5000]);
        $expectedInstance->z = 300;
        static::assertEquals($expectedInstance, $actualSkippedPrivate);
    }
}

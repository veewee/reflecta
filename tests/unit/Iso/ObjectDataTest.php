<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Iso;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Iso\object_data;

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
}

<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use AllowDynamicProperties;
use PHPUnit\Framework\TestCase;
use stdClass;
use function VeeWee\Reflecta\Reflect\object_is_dynamic;
use const PHP_VERSION_ID;

final class ObjectIsDynamicTest extends TestCase
{
    public function test_it_can_check_for_dynamic_objects(): void
    {
        if (PHP_VERSION_ID < 80200) {
            static::markTestSkipped('On PHP 8.2, all classes are safely dynamic');
        }

        $x = new #[AllowDynamicProperties] class {};
        $y = new class {};
        $s = new stdClass();

        static::assertTrue(object_is_dynamic($x));
        static::assertFalse(object_is_dynamic($y));
        static::assertTrue(object_is_dynamic($s));
    }

    public function test_it_can_check_for_dynamic_objects_in_php_81(): void
    {
        if (PHP_VERSION_ID >= 80200) {
            static::markTestSkipped('On PHP 8.2, all classes are safely dynamic');
        }

        $x = new #[AllowDynamicProperties] class {};
        $y = new class {};
        $s = new stdClass();

        static::assertTrue(object_is_dynamic($x));
        static::assertTrue(object_is_dynamic($y));
        static::assertTrue(object_is_dynamic($s));
    }
}

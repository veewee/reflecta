<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Predicate;

use AllowDynamicProperties;
use PHPUnit\Framework\TestCase;
use stdClass;
use function VeeWee\Reflecta\Reflect\object_info;
use function VeeWee\Reflecta\Reflect\Predicate\class_is_dynamic;
use const PHP_VERSION_ID;

final class ClassIsDynamicTest extends TestCase
{
    public function test_it_can_check_for_dynamic_objects(): void
    {
        if (PHP_VERSION_ID < 80200) {
            static::markTestSkipped('On PHP 8.2, all classes are safely dynamic');
        }

        $x = new #[AllowDynamicProperties] class {};
        $y = new class {};
        $s = new stdClass();

        static::assertTrue(object_info($x)->check(class_is_dynamic()));
        static::assertFalse(object_info($y)->check(class_is_dynamic()));
        static::assertTrue(object_info($s)->check(class_is_dynamic()));
    }

    public function test_it_can_check_for_dynamic_objects_in_php_81(): void
    {
        if (PHP_VERSION_ID >= 80200) {
            static::markTestSkipped('On PHP 8.2, all classes are safely dynamic');
        }

        $x = new #[AllowDynamicProperties] class {};
        $y = new class {};
        $s = new stdClass();

        static::assertTrue(object_info($x)->check(class_is_dynamic()));
        static::assertTrue(object_info($y)->check(class_is_dynamic()));
        static::assertTrue(object_info($z)->check(class_is_dynamic()));
    }
}

<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use AllowDynamicProperties;
use PHPUnit\Framework\TestCase;
use stdClass;
use function VeeWee\Reflecta\Reflect\class_is_dynamic;
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

        static::assertTrue(class_is_dynamic(get_class($x)));
        static::assertFalse(class_is_dynamic(get_class($y)));
        static::assertTrue(class_is_dynamic(get_class(($s))));
    }

    public function test_it_can_check_for_dynamic_objects_in_php_81(): void
    {
        if (PHP_VERSION_ID >= 80200) {
            static::markTestSkipped('On PHP 8.2, all classes are safely dynamic');
        }

        $x = new #[AllowDynamicProperties] class {};
        $y = new class {};
        $s = new stdClass();

        static::assertTrue(class_is_dynamic(get_class($x)));
        static::assertTrue(class_is_dynamic(get_class($y)));
        static::assertTrue(class_is_dynamic(get_class($s)));
    }
}

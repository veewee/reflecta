<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\TestFixtures\AbstractAttribute;
use VeeWee\Reflecta\TestFixtures\CustomAttribute;
use VeeWee\Reflecta\TestFixtures\InheritedCustomAttribute;
use function VeeWee\Reflecta\Reflect\class_has_attribute;

final class ClassHasAttributeTest extends TestCase
{
    public function test_it_can_check_for_attribute(): void
    {
        $x = new #[CustomAttribute] class {};
        $className = get_class($x);

        static::assertTrue(class_has_attribute($className, CustomAttribute::class));
        static::assertFalse(class_has_attribute($className, InheritedCustomAttribute::class));
    }

    public function test_it_can_check_for_attributes_of_subtype(): void
    {
        $x = new #[InheritedCustomAttribute] class {};
        $className = get_class($x);

        static::assertTrue(class_has_attribute($className, AbstractAttribute::class));
        static::assertTrue(class_has_attribute($className, InheritedCustomAttribute::class));
    }
}

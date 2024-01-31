<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Predicate;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\TestFixtures\AbstractAttribute;
use VeeWee\Reflecta\TestFixtures\CustomAttribute;
use VeeWee\Reflecta\TestFixtures\InheritedCustomAttribute;
use function VeeWee\Reflecta\Reflect\object_info;
use function VeeWee\Reflecta\Reflect\Predicate\class_has_attribute_of_type;

final class ClassHasAttributeOfTypeTest extends TestCase
{
    public function test_it_can_check_for_attribute(): void
    {
        $x = new #[CustomAttribute] class {};
        $info = object_info($x);

        static::assertTrue($info->check(class_has_attribute_of_type(CustomAttribute::class)));
        static::assertFalse($info->check(class_has_attribute_of_type(InheritedCustomAttribute::class)));
    }

    public function test_it_can_check_for_attributes_of_subtype(): void
    {
        $x = new #[InheritedCustomAttribute] class {};
        $info = object_info($x);

        static::assertTrue($info->check(class_has_attribute_of_type(AbstractAttribute::class)));
        static::assertTrue($info->check(class_has_attribute_of_type(InheritedCustomAttribute::class)));
    }
}

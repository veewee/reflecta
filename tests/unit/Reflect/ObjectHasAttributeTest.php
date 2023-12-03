<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\TestFixtures\AbstractAttribute;
use VeeWee\Reflecta\TestFixtures\CustomAttribute;
use VeeWee\Reflecta\TestFixtures\InheritedCustomAttribute;
use function VeeWee\Reflecta\Reflect\object_has_attribute;

final class ObjectHasAttributeTest extends TestCase
{
    public function test_it_can_check_for_attribute(): void
    {
        $x = new #[CustomAttribute] class {};

        static::assertTrue(object_has_attribute($x, CustomAttribute::class));
        static::assertFalse(object_has_attribute($x, InheritedCustomAttribute::class));
    }

    public function test_it_can_check_for_attributes_of_subtype(): void
    {
        $x = new #[InheritedCustomAttribute] class {};

        static::assertTrue(object_has_attribute($x, AbstractAttribute::class));
        static::assertTrue(object_has_attribute($x, InheritedCustomAttribute::class));
    }
}

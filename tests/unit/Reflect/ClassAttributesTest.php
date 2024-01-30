<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use PHPUnit\Framework\TestCase;
use ThisIsAnUnknownAttribute;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\TestFixtures\AbstractAttribute;
use VeeWee\Reflecta\TestFixtures\CustomAttribute;
use VeeWee\Reflecta\TestFixtures\InheritedCustomAttribute;
use function VeeWee\Reflecta\Reflect\class_attributes;

final class ClassAttributesTest extends TestCase
{
    public function test_it_can_get_attributes(): void
    {
        $x = new #[InheritedCustomAttribute, CustomAttribute] class {};

        $actual = class_attributes(get_class($x));

        static::assertCount(2, $actual);
        static::assertInstanceOf(InheritedCustomAttribute::class, $actual[0]);
        static::assertInstanceOf(CustomAttribute::class, $actual[1]);
    }

    public function test_it_can_get_attributes_of_type(): void
    {
        $x = new #[InheritedCustomAttribute, CustomAttribute] class {};

        $actual = class_attributes(get_class($x), InheritedCustomAttribute::class);

        static::assertCount(1, $actual);
        static::assertInstanceOf(InheritedCustomAttribute::class, $actual[0]);
    }

    public function test_it_can_get_attributes_of_subtype(): void
    {
        $x = new #[InheritedCustomAttribute] class {};

        $actual = class_attributes(get_class($x), AbstractAttribute::class);

        static::assertCount(1, $actual);
        static::assertInstanceOf(InheritedCustomAttribute::class, $actual[0]);
    }

    public function test_it_can_fail_on_attribute_instantiation(): void
    {
        $x = new #[ThisIsAnUnknownAttribute] class {};

        $this->expectException(UnreflectableException::class);
        $this->expectExceptionMessage('Unable to instantiate class ThisIsAnUnknownAttribute.');

        class_attributes(get_class($x));
    }
}

<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Predicate;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;
use VeeWee\Reflecta\Reflect\Type\Visibility;
use function VeeWee\Reflecta\Reflect\Predicate\property_visibility;

final class PropertyVisibilityTest extends TestCase
{
    public function test_it_knows_public_visibility_from_property(): void
    {
        $x = new class() {
            public int $z = 0;
        };
        $prop = ReflectedClass::fromObject($x)->property('z');

        static::assertTrue($prop->check(property_visibility(Visibility::Public)));
        static::assertFalse($prop->check(property_visibility(Visibility::Protected)));
        static::assertFalse($prop->check(property_visibility(Visibility::Private)));
    }

    public function test_it_knows_protected_visibility_from_property(): void
    {
        $x = new class() {
            protected int $z = 0;
        };
        $prop = ReflectedClass::fromObject($x)->property('z');

        static::assertTrue($prop->check(property_visibility(Visibility::Protected)));
        static::assertFalse($prop->check(property_visibility(Visibility::Public)));
        static::assertFalse($prop->check(property_visibility(Visibility::Private)));
    }

    public function test_it_knows_private_visibility_from_property(): void
    {
        $x = new class() {
            private int $z = 0;
        };
        $prop = ReflectedClass::fromObject($x)->property('z');

        static::assertTrue($prop->check(property_visibility(Visibility::Private)));
        static::assertFalse($prop->check(property_visibility(Visibility::Public)));
        static::assertFalse($prop->check(property_visibility(Visibility::Protected)));
    }
}

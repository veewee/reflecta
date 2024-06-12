<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Type;

use AllowDynamicProperties;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;
use VeeWee\Reflecta\Reflect\Type\ReflectedProperty;
use VeeWee\Reflecta\Reflect\Type\Visibility;
use VeeWee\Reflecta\TestFixtures\CustomAttribute;
use VeeWee\Reflecta\TestFixtures\X;

final class ReflectedPropertyTest extends TestCase
{
    public function test_it_can_get_the_name(): void
    {
        $x = (object) [
            'name' => 'value'
        ];

        $property = ReflectedClass::fromObject($x)->property('name');
        static::assertSame('name', $property->name());
    }

    public function test_it_can_get_declaring_class(): void
    {
        $class = ReflectedClass::fromFullyQualifiedClassName(X::class);
        $property = $class->property('z');

        static::assertEquals($class, $property->declaringClass());
    }

    public function test_it_knows_public_visibility_from_property(): void
    {
        $x = new class() {
            public int $z = 0;
        };
        $prop = ReflectedClass::fromObject($x)->property('z');
        $visibility = $prop->visibility();

        static::assertSame(Visibility::Public, $visibility);
        static::assertTrue($prop->isPublic());
        static::assertFalse($prop->isProtected());
        static::assertFalse($prop->isPrivate());
        static::assertTrue($prop->check(static fn (ReflectedProperty $property) => $property->isPublic()));
    }

    public function test_it_knows_protected_visibility_from_property(): void
    {
        $x = new class() {
            protected int $z = 0;
        };
        $prop = ReflectedClass::fromObject($x)->property('z');
        $visibility = $prop->visibility();

        static::assertSame(Visibility::Protected, $visibility);
        static::assertFalse($prop->isPublic());
        static::assertTrue($prop->isProtected());
        static::assertFalse($prop->isPrivate());
        static::assertTrue($prop->check(static fn (ReflectedProperty $property) => $property->isProtected()));
    }

    public function test_it_knows_private_visibility_from_property(): void
    {
        $x = new class() {
            private int $z = 0;
        };
        $prop = ReflectedClass::fromObject($x)->property('z');
        $visibility = $prop->visibility();

        static::assertSame(Visibility::Private, $visibility);
        static::assertFalse($prop->isPublic());
        static::assertFalse($prop->isProtected());
        static::assertTrue($prop->isPrivate());
        static::assertTrue($prop->check(static fn (ReflectedProperty $property) => $property->isPrivate()));
    }

    public function test_it_knows_promoted_property(): void
    {
        $x = new class(1) {
            private int $foo = 0;

            public function __construct(
                private int $bar
            ) {
            }
        };
        $class = ReflectedClass::fromObject($x);

        static::assertFalse($class->property('foo')->isPromoted());
        static::assertTrue($class->property('bar')->isPromoted());
    }

    public function test_it_knows_static_property(): void
    {
        $x = new class(1) {
            private int $foo = 0;
            private static int $bar = 0;
        };
        $class = ReflectedClass::fromObject($x);

        static::assertFalse($class->property('foo')->isStatic());
        static::assertTrue($class->property('bar')->isStatic());
    }

    public function test_it_knows_readonly_property(): void
    {
        $x = new class(1) {
            private int $foo = 0;
            private readonly int $bar;
        };
        $class = ReflectedClass::fromObject($x);

        static::assertFalse($class->property('foo')->isReadOnly());
        static::assertTrue($class->property('bar')->isReadOnly());
    }

    public function test_it_knows_is_default_property_on_dynamic_class(): void
    {
        if (PHP_VERSION_ID < 80200) {
            static::markTestSkipped('On PHP 8.2, all classes are safely dynamic');
        }

        $x = new #[AllowDynamicProperties] class() {
            private int $foo = 0;
        };
        $x->bar = 0;
        $class = ReflectedClass::fromObject($x);

        static::assertTrue($class->property('foo')->isDefault());
        static::assertFalse($class->property('foo')->isDynamic());
        static::assertFalse($class->property('bar')->isDefault());
        static::assertTrue($class->property('bar')->isDynamic());
    }

    public function test_it_knows_is_default_property(): void
    {
        if (PHP_VERSION_ID >= 80200) {
            static::markTestSkipped('On PHP 8.2, dynamic classes should be marked with #[AllowDynamicProperties] attribute');
        }

        $x = new class() {
            private int $foo = 0;
        };
        $x->bar = 0;
        $class = ReflectedClass::fromObject($x);

        static::assertTrue($class->property('foo')->isDefault());
        static::assertFalse($class->property('foo')->isDynamic());
        static::assertFalse($class->property('bar')->isDefault());
        static::assertTrue($class->property('bar')->isDynamic());
    }

    public function test_it_knows_default_values(): void
    {
        $x = new class() {
            private int $foo = 0;
            private int $bar;
        };
        $class = ReflectedClass::fromObject($x);

        static::assertTrue($class->property('foo')->hasDefaultValue());
        static::assertSame(0, $class->property('foo')->defaultValue());
        static::assertFalse($class->property('bar')->hasDefaultValue());
        static::assertNull($class->property('bar')->defaultValue());
    }

    public function test_it_knows_about_docblocks(): void
    {
        $x = new class() {
            /** docblock */
            private int $foo = 0;
        };
        $class = ReflectedClass::fromObject($x);

        static::assertSame('/** docblock */', $class->property('foo')->docComment());
    }

    public function test_it_knows_about_attributes(): void
    {
        $x = new class() {
            #[CustomAttribute]
            private int $foo = 0;
        };
        $prop = ReflectedClass::fromObject($x)->property('foo');

        $attributes = $prop->attributes();
        $filteredAttributes = $prop->attributes(CustomAttribute::class);

        static::assertTrue($prop->hasAttributeOfType(CustomAttribute::class));
        static::assertEquals($attributes, $filteredAttributes);
        static::assertEquals([new CustomAttribute()], $attributes);
    }

    public function test_it_can_apply(): void
    {
        $x = new class() {
            private int $foo = 0;
        };
        $prop = ReflectedClass::fromObject($x)->property('foo');

        $result = $prop->apply(
            static fn (ReflectionProperty $prop): string => $prop->getName()
        );

        static::assertSame('foo', $result);
    }
}

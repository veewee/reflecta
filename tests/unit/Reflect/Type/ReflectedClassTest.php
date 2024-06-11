<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Type;

use AllowDynamicProperties;
use PHPUnit\Framework\TestCase;
use ThisIsAnUnknownAttribute;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;
use VeeWee\Reflecta\Reflect\Type\ReflectedProperty;
use VeeWee\Reflecta\TestFixtures\AbstractAttribute;
use VeeWee\Reflecta\TestFixtures\CustomAttribute;
use VeeWee\Reflecta\TestFixtures\InheritedCustomAttribute;
use VeeWee\Reflecta\TestFixtures\X;

final class ReflectedClassTest extends TestCase
{
    public function test_it_can_load_class_from_fqcn(): void
    {
        static::assertSame(X::class, ReflectedClass::fromFullyQualifiedClassName(X::class)->fullName());
        static::assertSame(X::class, ReflectedClass::from(X::class)->fullName());
    }

    public function test_it_can_not_load_from_fqcn(): void
    {
        $this->expectException(UnreflectableException::class);
        $this->expectExceptionMessage('Unable to locate class UnknownClass.');

        ReflectedClass::fromFullyQualifiedClassName('UnknownClass');
    }

    public function test_it_can_load_class_from_object(): void
    {
        $object = new X();

        static::assertSame(X::class, ReflectedClass::fromObject($object)->fullName());
        static::assertSame(X::class, ReflectedClass::from($object)->fullName());
    }

    public function test_it_knows_name_parts(): void
    {
        $class = ReflectedClass::fromFullyQualifiedClassName(X::class);

        static::assertSame(X::class, $class->fullName());
        static::assertSame('X', $class->shortName());
        static::assertSame('VeeWee\Reflecta\TestFixtures', $class->namespaceName());
    }

    public function test_it_knows_it_is_final(): void
    {
        $x = ReflectedClass::fromFullyQualifiedClassName(X::class);
        $y = ReflectedClass::fromObject(new class() {});

        static::assertTrue($x->isFinal());
        static::assertTrue($x->check(static fn (ReflectedClass $class) => $class->isFinal()));
        static::assertFalse($y->isFinal());
        static::assertFalse($y->check(static fn (ReflectedClass $class) => $class->isFinal()));
    }

    public function test_it_knows_readonly_classes(): void
    {
        if (PHP_VERSION_ID < 80300) {
            static::markTestSkipped('On PHP 8.3, readonly classes are allowed');
        }

        $x = ReflectedClass::fromObject(new readonly class() {});
        $y = ReflectedClass::fromObject(new class() {});

        static::assertTrue($x->isReadOnly());
        static::assertTrue($x->check(static fn (ReflectedClass $class) => $class->isReadOnly()));
        static::assertFalse($y->isReadOnly());
        static::assertFalse($y->check(static fn (ReflectedClass $class) => $class->isReadOnly()));
    }

    public function test_it_knows_about_dynamic_classes(): void
    {
        if (PHP_VERSION_ID < 80200) {
            static::markTestSkipped('On PHP <8.2, all classes are dynamic');
        }

        $x = ReflectedClass::fromObject(new #[AllowDynamicProperties] class() {});
        $y = ReflectedClass::fromObject(new class() {});

        static::assertTrue($x->isDynamic());
        static::assertTrue($x->check(static fn (ReflectedClass $class) => $class->isDynamic()));
        static::assertFalse($y->isDynamic());
        static::assertFalse($y->check(static fn (ReflectedClass $class) => $class->isDynamic()));
    }

    public function test_it_knows_if_class_is_abstract(): void
    {
        $abstract = ReflectedClass::fromFullyQualifiedClassName(AbstractAttribute::class);
        $concrete = ReflectedClass::fromObject(new class() {});

        static::assertTrue($abstract->isAbstract());
        static::assertTrue($abstract->check(static fn (ReflectedClass $class) => $class->isAbstract()));
        static::assertFalse($concrete->isAbstract());
        static::assertFalse($concrete->check(static fn (ReflectedClass $class) => $class->isAbstract()));
    }

    public function test_it_knows_it_is_instantiable(): void
    {
        $abstract = ReflectedClass::fromFullyQualifiedClassName(AbstractAttribute::class);
        $concrete = ReflectedClass::fromObject(new class() {});

        static::assertFalse($abstract->isInstantiable());
        static::assertFalse($abstract->check(static fn (ReflectedClass $class) => $class->isInstantiable()));
        static::assertTrue($concrete->isInstantiable());
        static::assertTrue($concrete->check(static fn (ReflectedClass $class) => $class->isInstantiable()));
    }

    public function test_it_knows_it_is_cloneable(): void
    {
        $abstract = ReflectedClass::fromFullyQualifiedClassName(AbstractAttribute::class);
        $concrete = ReflectedClass::fromObject(new class() {});

        static::assertFalse($abstract->isCloneable());
        static::assertFalse($abstract->check(static fn (ReflectedClass $class) => $class->isCloneable()));
        static::assertTrue($concrete->isCloneable());
        static::assertTrue($concrete->check(static fn (ReflectedClass $class) => $class->isCloneable()));
    }

    public function test_it_knows_about_doc_comment(): void
    {
        $x = ReflectedClass::fromObject(/** doc */ new class() {});

        static::assertSame('/** doc */', $x->docComment());
    }

    public function test_it_can_grab_property_by_name(): void
    {
        $class = ReflectedClass::fromFullyQualifiedClassName(X::class);
        $property = $class->property('z');

        static::assertSame('z', $property->name());
    }

    public function test_it_can_list_all_properties(): void
    {
        $class = ReflectedClass::fromFullyQualifiedClassName(X::class);
        $properties = $class->properties();

        static::assertCount(1, $properties);
        static::assertSame('z', $properties['z']->name());
    }

    public function test_it_can_filter_properties(): void
    {
        $class = ReflectedClass::fromFullyQualifiedClassName(X::class);
        $publicProps = $class->properties(static fn (ReflectedProperty $prop): bool => $prop->isPublic());
        $privateProps = $class->properties(static fn (ReflectedProperty $prop): bool => $prop->isPrivate());

        static::assertCount(1, $publicProps);
        static::assertSame('z', $publicProps['z']->name());
        static::assertCount(0, $privateProps);
    }

    public function test_it_can_get_attributes(): void
    {
        $x = new #[InheritedCustomAttribute, CustomAttribute] class {};

        $actual = ReflectedClass::fromObject($x)->attributes();

        static::assertCount(2, $actual);
        static::assertInstanceOf(InheritedCustomAttribute::class, $actual[0]);
        static::assertInstanceOf(CustomAttribute::class, $actual[1]);
    }

    public function test_it_can_get_attributes_of_type(): void
    {
        $x = new #[InheritedCustomAttribute, CustomAttribute] class {};

        $class = ReflectedClass::fromObject($x);
        $actual = $class->attributes(InheritedCustomAttribute::class);

        static::assertCount(1, $actual);
        static::assertTrue($class->hasAttributeOfType(InheritedCustomAttribute::class));
        static::assertInstanceOf(InheritedCustomAttribute::class, $actual[0]);
    }

    public function test_it_can_get_attributes_of_subtype(): void
    {
        $x = new #[InheritedCustomAttribute] class {};

        $class = ReflectedClass::fromObject($x);
        $actual = $class->attributes(AbstractAttribute::class);

        static::assertCount(1, $actual);
        static::assertTrue($class->hasAttributeOfType(AbstractAttribute::class));
        static::assertInstanceOf(InheritedCustomAttribute::class, $actual[0]);
    }

    public function test_it_can_fail_on_attribute_instantiation(): void
    {
        $x = new #[ThisIsAnUnknownAttribute] class {};
        $class = ReflectedClass::fromObject($x);

        $this->expectException(UnreflectableException::class);
        $this->expectExceptionMessage('Unable to instantiate class ThisIsAnUnknownAttribute.');

        $class->attributes();
    }
}

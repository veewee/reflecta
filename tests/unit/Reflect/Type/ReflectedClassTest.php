<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Type;

use AllowDynamicProperties;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use stdClass;
use ThisIsAnUnknownAttribute;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;
use VeeWee\Reflecta\Reflect\Type\ReflectedProperty;
use VeeWee\Reflecta\TestFixtures\AbstractAttribute;
use VeeWee\Reflecta\TestFixtures\AbstractProperties;
use VeeWee\Reflecta\TestFixtures\CustomAttribute;
use VeeWee\Reflecta\TestFixtures\Dynamic;
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

    public function test_it_knows_parent(): void
    {
        $withParent = new class() extends AbstractAttribute {};
        $withParentParent = ReflectedClass::fromObject($withParent)->parent();
        $withoutParent = new class() {};
        $withoutParentParent = ReflectedClass::fromObject($withoutParent)->parent();

        static::assertTrue($withParentParent->isSome());
        static::assertEquals($withParentParent->unwrap(), ReflectedClass::fromFullyQualifiedClassName(AbstractAttribute::class));
        static::assertTrue($withoutParentParent->isNone());
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

    public function test_it_can_check_for_dynamic_objects(): void
    {
        if (PHP_VERSION_ID < 80200) {
            static::markTestSkipped('On PHP 8.2, all classes are safely dynamic');
        }

        $x = new #[AllowDynamicProperties] class {};
        $y = new class {};
        $s = new stdClass();

        static::assertTrue(ReflectedClass::fromObject($x)->isDynamic());
        static::assertFalse(ReflectedClass::fromObject($y)->isDynamic());
        static::assertTrue(ReflectedClass::fromObject($s)->isDynamic());
    }

    public function test_it_can_check_for_dynamic_objects_in_php_81(): void
    {
        if (PHP_VERSION_ID >= 80200) {
            static::markTestSkipped('On PHP 8.2, all classes are safely dynamic');
        }

        $x = new #[AllowDynamicProperties] class {};
        $y = new class {};
        $s = new stdClass()
        ;
        static::assertTrue(ReflectedClass::fromObject($x)->isDynamic());
        static::assertTrue(ReflectedClass::fromObject($y)->isDynamic());
        static::assertTrue(ReflectedClass::fromObject($s)->isDynamic());
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

    public function test_it_can_grab_inherited_property_by_name(): void
    {
        $x = new class extends AbstractProperties {};
        $class = ReflectedClass::fromObject($x);
        $property = $class->property('a');

        static::assertSame('a', $property->name());
    }

    public function test_it_can_list_all_properties(): void
    {
        $class = ReflectedClass::fromFullyQualifiedClassName(X::class);
        $properties = $class->properties();

        static::assertCount(1, $properties);
        static::assertSame('z', $properties['z']->name());
    }

    public function test_it_can_list_dynamic_properties(): void
    {
        $x = new Dynamic();
        $x->x = 'foo';
        $x->y = 'bar';
        $class = ReflectedClass::fromObject($x);
        $properties = $class->properties();

        static::assertCount(2, $properties);
        static::assertSame('x', $properties['x']->name());
        static::assertSame('y', $properties['y']->name());
    }

    public function test_it_can_list_inherited_properties(): void
    {
        $x = new class extends AbstractProperties {
            private string $d = 'd';
            protected string $e = 'e';
            public string $f = 'f';
        };

        $class = ReflectedClass::fromObject($x);
        $properties = $class->properties();

        static::assertCount(6, $properties);
        static::assertSame('a', $properties['a']->name());
        static::assertSame(AbstractProperties::class, $properties['a']->declaringClass()->fullName());
        static::assertSame('b', $properties['b']->name());
        static::assertSame(AbstractProperties::class, $properties['b']->declaringClass()->fullName());
        static::assertSame('c', $properties['c']->name());
        static::assertSame(AbstractProperties::class, $properties['c']->declaringClass()->fullName());
        static::assertSame('d', $properties['d']->name());
        static::assertSame($x::class, $properties['d']->declaringClass()->fullName());
        static::assertSame('e', $properties['e']->name());
        static::assertSame($x::class, $properties['e']->declaringClass()->fullName());
        static::assertSame('f', $properties['f']->name());
        static::assertSame($x::class, $properties['f']->declaringClass()->fullName());
    }

    public function test_it_can_overwrite_inherited_props(): void
    {
        $x = new class extends AbstractProperties {
            private string $a = 'd';
            protected string $b = 'e';
            public string $c = 'f';
        };

        $class = ReflectedClass::fromObject($x);
        $properties = $class->properties();

        static::assertCount(3, $properties);
        static::assertSame('a', $properties['a']->name());
        static::assertSame($x::class, $properties['a']->declaringClass()->fullName());
        static::assertSame('b', $properties['b']->name());
        static::assertSame($x::class, $properties['b']->declaringClass()->fullName());
        static::assertSame('c', $properties['c']->name());
        static::assertSame($x::class, $properties['c']->declaringClass()->fullName());
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

    public function test_it_can_apply(): void
    {
        $class = ReflectedClass::fromFullyQualifiedClassName(X::class);

        $result = $class->apply(
            static fn (ReflectionClass $class): string => $class->getName()
        );

        static::assertSame(X::class, $result);
    }
}

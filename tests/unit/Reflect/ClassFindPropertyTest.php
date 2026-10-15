<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;
use VeeWee\Reflecta\TestFixtures\Optional\AbstractSecretParent;
use VeeWee\Reflecta\TestFixtures\Optional\DynamicSecretSubject;
use VeeWee\Reflecta\TestFixtures\Optional\DynamicSubject;
use VeeWee\Reflecta\TestFixtures\Optional\TypedSubject;
use function VeeWee\Reflecta\Reflect\class_find_property;

final class ClassFindPropertyTest extends TestCase
{
    #[DataProvider('provideDeclaredProperties')]
    public function test_it_finds_the_declared_property(string $className, string $name, string $declaringClass, object $object, mixed $value): void
    {
        $property = class_find_property($className, $name);

        static::assertNotNull($property);
        static::assertSame($name, $property->getName());
        static::assertSame($declaringClass, $property->getDeclaringClass()->getName());
        static::assertSame($value, $property->getValue($object));
    }

    #[DataProvider('provideUndeclaredProperties')]
    public function test_it_returns_null_when_no_class_declares_the_property(string $className, string $name): void
    {
        static::assertNull(class_find_property($className, $name));
    }

    public function test_it_finds_static_properties(): void
    {
        $property = class_find_property(TypedSubject::class, 'static');

        static::assertNotNull($property);
        static::assertTrue($property->isStatic());
        static::assertSame('static', $property->getValue());
    }

    public static function provideDeclaredProperties(): iterable
    {
        yield 'own public' => [TypedSubject::class, 'public', TypedSubject::class, new TypedSubject(), 'public'];
        yield 'own private' => [TypedSubject::class, 'private', TypedSubject::class, new TypedSubject(), 'private'];
        yield 'inherited protected' => [TypedSubject::class, 'inherited', AbstractSecretParent::class, new TypedSubject(), 'parent-protected'];
        yield 'private in parent' => [TypedSubject::class, 'secret', AbstractSecretParent::class, new TypedSubject(), 'parent-private'];
        yield 'private in parent of a dynamic class' => [DynamicSecretSubject::class, 'secret', AbstractSecretParent::class, new DynamicSecretSubject(), 'parent-private'];
        yield 'declared on a dynamic class' => [DynamicSubject::class, 'declared', DynamicSubject::class, new DynamicSubject(), 'declared'];
    }

    public static function provideUndeclaredProperties(): iterable
    {
        yield 'undeclared' => [TypedSubject::class, 'nope'];
        yield 'undeclared with a parent' => [DynamicSecretSubject::class, 'nope'];
        yield 'undeclared on a dynamic class' => [DynamicSubject::class, 'extra'];
        yield 'stdClass' => [stdClass::class, 'a'];
    }
}

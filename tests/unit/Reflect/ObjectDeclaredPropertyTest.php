<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\TestFixtures\Optional\AbstractSecretParent;
use VeeWee\Reflecta\TestFixtures\Optional\DynamicSecretSubject;
use VeeWee\Reflecta\TestFixtures\Optional\DynamicSubject;
use VeeWee\Reflecta\TestFixtures\Optional\TypedSubject;
use function VeeWee\Reflecta\Reflect\object_declared_property;

final class ObjectDeclaredPropertyTest extends TestCase
{
    #[DataProvider('provideDeclaredProperties')]
    public function test_it_finds_the_declared_property(object $object, string $name, string $declaringClass, mixed $value): void
    {
        $property = object_declared_property($object, $name);

        static::assertNotNull($property);
        static::assertSame($name, $property->getName());
        static::assertSame($declaringClass, $property->getDeclaringClass()->getName());
        static::assertSame($value, $property->getValue($object));
    }

    #[DataProvider('provideUndeclaredProperties')]
    public function test_it_returns_null_when_no_class_declares_the_property(object $object, string $name): void
    {
        static::assertNull(object_declared_property($object, $name));
    }

    public function test_it_finds_static_properties(): void
    {
        $property = object_declared_property(new TypedSubject(), 'static');

        static::assertNotNull($property);
        static::assertTrue($property->isStatic());
        static::assertSame('static', $property->getValue());
    }

    public static function provideDeclaredProperties(): iterable
    {
        $shadowed = new DynamicSecretSubject();
        $shadowed->{'secret'} = 'dynamic';

        yield 'own public' => [new TypedSubject(), 'public', TypedSubject::class, 'public'];
        yield 'own private' => [new TypedSubject(), 'private', TypedSubject::class, 'private'];
        yield 'inherited protected' => [new TypedSubject(), 'inherited', AbstractSecretParent::class, 'parent-protected'];
        yield 'private in parent' => [new TypedSubject(), 'secret', AbstractSecretParent::class, 'parent-private'];
        yield 'private in parent shadowed by a dynamic property' => [$shadowed, 'secret', AbstractSecretParent::class, 'parent-private'];
        yield 'declared on a dynamic class' => [new DynamicSubject(), 'declared', DynamicSubject::class, 'declared'];
    }

    public static function provideUndeclaredProperties(): iterable
    {
        $dynamic = new DynamicSubject();
        $dynamic->{'extra'} = 'dynamic';

        yield 'undeclared' => [new TypedSubject(), 'nope'];
        yield 'undeclared with a parent' => [new DynamicSecretSubject(), 'nope'];
        yield 'dynamic' => [$dynamic, 'extra'];
        yield 'stdClass' => [(object) ['a' => 'A'], 'a'];
    }
}

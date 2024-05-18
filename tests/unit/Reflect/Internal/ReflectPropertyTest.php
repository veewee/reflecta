<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Internal;

use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use stdClass;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\Internal\reflect_property;

final class ReflectPropertyTest extends TestCase
{

    public function test_it_errors_on_unkown_object_property(): void
    {
        $this->expectException(UnreflectableException::class);
        reflect_property(new X(), 'foo');
    }


    public function test_it_errors_on_unkown_class_property(): void
    {
        $this->expectException(UnreflectableException::class);
        reflect_property(X::class, 'foo');
    }

    public function it_returns_reflection_property_of_class()
    {
        $rp = reflect_property(X::class, 'z');

        static::assertInstanceOf(ReflectionProperty::class, $rp);
        static::assertSame('z', $rp->getName());
    }

    public function it_returns_reflection_property_of_object()
    {
        $rp = reflect_property(new X(), 'z');

        static::assertInstanceOf(ReflectionProperty::class, $rp);
        static::assertSame('z', $rp->getName());
    }

    public function it_returns_reflection_property_of_dynamic_object()
    {
        $object = new stdClass();
        $object->z = 'hello';

        $rp = reflect_property($object, 'z');

        static::assertInstanceOf(ReflectionProperty::class, $rp);
        static::assertSame('z', $rp->getName());
    }

    public function it_errors_on_unkown_property_of_dynamic_object()
    {
        $object = new stdClass();
        $object->z = 'hello';

        $this->expectException(UnreflectableException::class);
        reflect_property($object, 'unkown');
    }
}

<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Internal;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\reflect_property;

class ReflectPropertyTest extends TestCase
{
    /** @test */
    public function it_errors_on_unkown_object_property(): void
    {
        $this->expectException(UnreflectableException::class);
        reflect_property(new X(), 'foo');
    }

    /** @test */
    public function it_errors_on_unkown_class_property(): void
    {
        $this->expectException(UnreflectableException::class);
        reflect_property(X::class, 'foo');
    }

    public function it_returns_reflection_property_of_class()
    {
        $rp = reflect_property(X::class, 'z');

        self::assertInstanceOf(\ReflectionProperty::class, $rp);
        self::assertSame('z', $rp->getName());
    }

    public function it_returns_reflection_property_of_object()
    {
        $rp = reflect_property(new X(), 'z');

        self::assertInstanceOf(\ReflectionProperty::class, $rp);
        self::assertSame('z', $rp->getName());
    }

    public function it_returns_reflection_property_of_dynamic_object()
    {
        $object = new \stdClass();
        $object->z = 'hello';

        $rp = reflect_property($object, 'z');

        self::assertInstanceOf(\ReflectionProperty::class, $rp);
        self::assertSame('z', $rp->getName());
    }

    public function it_errors_on_unkown_property_of_dynamic_object()
    {
        $object = new \stdClass();
        $object->z = 'hello';

        $this->expectException(UnreflectableException::class);
        $rp = reflect_property($object, 'unkown');
    }
}

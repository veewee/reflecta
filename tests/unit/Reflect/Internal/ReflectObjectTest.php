<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Internal;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\reflect_class;
use function VeeWee\Reflecta\Reflect\reflect_object;

class ReflectObjectTest extends TestCase
{
    /** @test */
    public function it_errors(): void
    {
        $this->expectException(UnreflectableException::class);
        reflect_object(null);
    }

    public function it_returns_reflection_class()
    {
        $rc = reflect_object(new X());

        self::assertInstanceOf(\ReflectionObject::class, $rc);
        self::assertSame(X::class, $rc->getName());
    }

    public function it_can_deal_with_stdClass()
    {
        $rc = reflect_object(new StdClass());

        self::assertInstanceOf(\ReflectionObject::class, $rc);
        self::assertSame(StdClass::class, $rc->getName());
    }
}

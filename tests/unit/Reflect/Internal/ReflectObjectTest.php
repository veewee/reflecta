<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Internal;

use PHPUnit\Framework\TestCase;
use ReflectionObject;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\reflect_object;

final class ReflectObjectTest extends TestCase
{

    public function test_it_errors(): void
    {
        static::markTestIncomplete('TODO : find an unreflectable object');

        $this->expectException(UnreflectableException::class);
        reflect_object(null);
    }

    public function it_returns_reflection_class()
    {
        $rc = reflect_object(new X());

        static::assertInstanceOf(ReflectionObject::class, $rc);
        static::assertSame(X::class, $rc->getName());
    }

    public function it_can_deal_with_stdClass()
    {
        $rc = reflect_object(new StdClass());

        static::assertInstanceOf(ReflectionObject::class, $rc);
        static::assertSame(StdClass::class, $rc->getName());
    }
}

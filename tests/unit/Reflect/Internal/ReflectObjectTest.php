<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Internal;

use PHPUnit\Framework\TestCase;
use ReflectionObject;
use stdClass;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\Internal\reflect_object;

final class ReflectObjectTest extends TestCase
{

    public function test_it_returns_reflection_class()
    {
        $rc = reflect_object(new X());

        static::assertInstanceOf(ReflectionObject::class, $rc);
        static::assertSame(X::class, $rc->getName());
    }


    public function test_it_can_deal_with_std_class()
    {
        $rc = reflect_object(new stdClass());

        static::assertInstanceOf(ReflectionObject::class, $rc);
        static::assertSame(stdClass::class, $rc->getName());
    }
}

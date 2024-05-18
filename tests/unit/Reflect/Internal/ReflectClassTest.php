<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Internal;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use function VeeWee\Reflecta\Reflect\Internal\reflect_class;

final class ReflectClassTest extends TestCase
{

    public function test_it_errors(): void
    {
        $this->expectException(UnreflectableException::class);
        reflect_class('UnkownClass');
    }

    public function it_returns_reflection_class()
    {
        $rc = reflect_class(X::class);

        static::assertInstanceOf(ReflectionClass::class, $rc);
        static::assertSame(X::class, $rc->getName());
    }
}

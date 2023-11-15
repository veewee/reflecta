<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Internal;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use function VeeWee\Reflecta\Reflect\reflect_class;

class ReflectClassTest extends TestCase
{
    /** @test */
    public function it_errors(): void
    {
        $this->expectException(UnreflectableException::class);
        reflect_class('UnkownClass');
    }

    public function it_returns_reflection_class()
    {
        $rc = reflect_class(X::class);

        self::assertInstanceOf(\ReflectionClass::class, $rc);
        self::assertSame(X::class, $rc->getName());
    }
}

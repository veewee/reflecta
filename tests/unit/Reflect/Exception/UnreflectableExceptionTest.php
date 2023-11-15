<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Exception;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Exception\RuntimeException;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;

class UnreflectableExceptionTest extends TestCase
{
    /** @test */
    public function it_can_throw_unkown_property(): void
    {
        $previous = new \Exception('hey');
        $exception = UnreflectableException::unknownProperty('class', 'prop', $previous);

        self::assertSame($previous, $exception->getPrevious());
        self::assertSame(0, $exception->getCode());
        $this->expectExceptionObject($exception);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to locate property class::$prop.');

        throw $exception;
    }

    /** @test */
    public function it_can_throw_non_instantiatable_class(): void
    {
        $previous = new \Exception('hey');
        $exception = UnreflectableException::nonInstantiatable('className', $previous);

        self::assertSame($previous, $exception->getPrevious());
        self::assertSame(0, $exception->getCode());
        $this->expectExceptionObject($exception);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to instantiate class className.');

        throw $exception;
    }

    /** @test */
    public function it_can_throw_unkown_class(): void
    {
        $previous = new \Exception('hey');
        $exception = UnreflectableException::unknownClass('className', $previous);

        self::assertSame($previous, $exception->getPrevious());
        self::assertSame(0, $exception->getCode());
        $this->expectExceptionObject($exception);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to locate class className.');

        throw $exception;
    }

    /** @test */
    public function it_can_throw_unwritable_property(): void
    {
        $previous = new \Exception('hey');
        $exception = UnreflectableException::unwritableProperty('class', 'prop', 'string', $previous);

        self::assertSame($previous, $exception->getPrevious());
        self::assertSame(0, $exception->getCode());
        $this->expectExceptionObject($exception);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to write type string to property class::$prop.');

        throw $exception;
    }
}

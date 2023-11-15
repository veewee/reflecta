<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Exception;

use Exception;
use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Exception\RuntimeException;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;

final class UnreflectableExceptionTest extends TestCase
{
    
    public function test_it_can_throw_unkown_property(): void
    {
        $previous = new Exception('hey');
        $exception = UnreflectableException::unknownProperty('class', 'prop', $previous);

        static::assertSame($previous, $exception->getPrevious());
        static::assertSame(0, $exception->getCode());
        $this->expectExceptionObject($exception);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to locate property class::$prop.');

        throw $exception;
    }

    
    public function test_it_can_throw_non_instantiatable_class(): void
    {
        $previous = new Exception('hey');
        $exception = UnreflectableException::nonInstantiatable('className', $previous);

        static::assertSame($previous, $exception->getPrevious());
        static::assertSame(0, $exception->getCode());
        $this->expectExceptionObject($exception);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to instantiate class className.');

        throw $exception;
    }

    
    public function test_it_can_throw_unkown_class(): void
    {
        $previous = new Exception('hey');
        $exception = UnreflectableException::unknownClass('className', $previous);

        static::assertSame($previous, $exception->getPrevious());
        static::assertSame(0, $exception->getCode());
        $this->expectExceptionObject($exception);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to locate class className.');

        throw $exception;
    }

    
    public function test_it_can_throw_unwritable_property(): void
    {
        $previous = new Exception('hey');
        $exception = UnreflectableException::unwritableProperty('class', 'prop', 'string', $previous);

        static::assertSame($previous, $exception->getPrevious());
        static::assertSame(0, $exception->getCode());
        $this->expectExceptionObject($exception);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to write type string to property class::$prop.');

        throw $exception;
    }
}

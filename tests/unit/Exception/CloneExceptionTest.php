<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Exception;

use Exception;
use PHPUnit\Framework\TestCase;
use StdClass;
use VeeWee\Reflecta\Exception\CloneException;
use VeeWee\Reflecta\Exception\RuntimeException;

final class CloneExceptionTest extends TestCase
{
    
    public function test_it_can_throw_clone_error(): void
    {
        $previous = new Exception('hey');
        $exception = CloneException::impossibleToClone(new StdClass(), $previous);

        static::assertSame($previous, $exception->getPrevious());
        static::assertSame(0, $exception->getCode());
        $this->expectExceptionObject($exception);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Impossible to clone type');

        throw $exception;
    }
}

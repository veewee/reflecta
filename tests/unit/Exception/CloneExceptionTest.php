<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Exception;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Exception\CloneException;
use VeeWee\Reflecta\Exception\RuntimeException;

class CloneExceptionTest extends TestCase
{
    /** @test */
    public function it_can_throw_clone_error(): void
    {
        $previous = new \Exception('hey');
        $exception = CloneException::impossibleToClone(new \StdClass(), $previous);

        self::assertSame($previous, $exception->getPrevious());
        self::assertSame(0, $exception->getCode());
        $this->expectExceptionObject($exception);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Impossible to clone type');

        throw $exception;
    }
}

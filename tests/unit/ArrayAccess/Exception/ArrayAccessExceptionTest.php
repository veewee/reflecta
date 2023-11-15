<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\ArrayAccess\Exception;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\ArrayAccess\Exception\ArrayAccessException;
use VeeWee\Reflecta\Exception\RuntimeException;

class ArrayAccessExceptionTest extends TestCase
{
    /** @test */
    public function it_can_throw_invalid_array_access(): void
    {
        $exception = ArrayAccessException::cannotAccessIndex('x');
        $this->expectExceptionObject($exception);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Impossible to access array at index x.');

        throw $exception;
    }
}

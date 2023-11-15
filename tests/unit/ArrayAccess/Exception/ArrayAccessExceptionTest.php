<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\ArrayAccess\Exception;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\ArrayAccess\Exception\ArrayAccessException;
use VeeWee\Reflecta\Exception\RuntimeException;

final class ArrayAccessExceptionTest extends TestCase
{
    
    public function test_it_can_throw_invalid_array_access(): void
    {
        $exception = ArrayAccessException::cannotAccessIndex('x');
        $this->expectExceptionObject($exception);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Impossible to access array at index x.');

        throw $exception;
    }
}

<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Exception;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Exception\ReadonlyException;
use VeeWee\Reflecta\Exception\RuntimeException;

final class ReadonlyExceptionTest extends TestCase
{
    
    public function test_it_can_throw_readonly_error(): void
    {
        $exception = ReadonlyException::couldNotWrite();

        $this->expectExceptionObject($exception);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not write to the provided lens: it is readonly.');

        throw $exception;
    }
}

<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Exception;

final class ReadonlyException extends RuntimeException
{
    public static function couldNotWrite(): self
    {
        return new self('Could not write to the provided lens: it is readonly.');
    }
}

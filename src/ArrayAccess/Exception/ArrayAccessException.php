<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\ArrayAccess\Exception;

use VeeWee\Reflecta\Exception\RuntimeException;

class ArrayAccessException extends RuntimeException
{
    /**
     * @param array-key $index
     * @return self
     */
    public static function cannotAccessIndex($index): self
    {
        return new self(
            sprintf('Impossible to access array at index %s.', $index)
        );
    }
}

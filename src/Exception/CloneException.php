<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Exception;
final class CloneException extends \RuntimeException
{
    public static function impossibleToClone(mixed $object): self
    {
        return new self(sprintf('Impossible to clone type %s', get_debug_type($object)));
    }
}

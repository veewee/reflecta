<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Exception;

class UnreflectableException extends \RuntimeException
{
    public static function unknownProperty(string $className, string $property): self
    {
        return new self(sprintf('Unable to locate property %s::$%s', $className, $property));
    }
}

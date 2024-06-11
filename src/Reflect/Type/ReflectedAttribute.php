<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect\Type;

use ReflectionAttribute;
use Throwable;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;

final class ReflectedAttribute
{
    public function __construct(
        private readonly ReflectionAttribute $attribute
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function fullName() : string
    {
        return $this->attribute->getName();
    }

    public function isRepeated() : bool
    {
        return $this->attribute->isRepeated();
    }

    public function instantiate(): object
    {
        try {
            return $this->attribute->newInstance();
        } catch (Throwable $error) {
            throw UnreflectableException::nonInstantiatable(
                $this->attribute->getName(),
                $error
            );
        }
    }
}

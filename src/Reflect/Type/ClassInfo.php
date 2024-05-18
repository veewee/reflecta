<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect\Type;

use Closure;
use ReflectionClass;

final class ClassInfo
{
    public function __construct(
        private readonly ReflectionClass $class
    ) {
    }

    /**
     * @return class-string
     */
    public function fullName(): string
    {
        return $this->class->getName();
    }

    public function shortName(): string
    {
        return $this->class->getShortName();
    }

    public function namespaceName(): string
    {
        return $this->class->getNamespaceName();
    }

    /**
     * @param Closure(ClassInfo): bool $predicate
     */
    public function check(Closure $predicate): bool
    {
        return $predicate($this);
    }

    public function isFinal(): bool
    {
        return $this->class->isFinal();
    }

    public function isReadOnly(): bool
    {
        // Readonly classes is a PHP 8.2 feature.
        // In previous versions, all objects are not readonly
        if (PHP_VERSION_ID < 80_20_0 || !method_exists($this->class, 'isReadOnly')) {
            return false;
        }

        return (bool) $this->class->isReadOnly();
    }

    public function isAbstract(): bool
    {
        return $this->class->isAbstract();
    }

    public function isInstantiable(): bool
    {
        return $this->class->isInstantiable();
    }

    public function isCloneable(): bool
    {
        return $this->class->isCloneable();
    }

    public function docComment(): string
    {
        return $this->class->getDocComment();
    }
}

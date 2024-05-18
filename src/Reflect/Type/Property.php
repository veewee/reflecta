<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect\Type;

use Closure;
use ReflectionProperty;

final class Property
{
    public function __construct(
        private readonly ReflectionProperty $property
    ) {
    }

    public function name(): string
    {
        return $this->property->getName();
    }

    public function declaringClass(): ClassInfo
    {
        return new ClassInfo($this->property->getDeclaringClass());
    }

    public function visibility(): Visibility
    {
        return Visibility::forProperty($this->property);
    }

    /**
     * @param Closure(Property): bool $predicate
     */
    public function check(Closure $predicate): bool
    {
        return $predicate($this);
    }

    public function isPublic(): bool
    {
        return $this->property->isPublic();
    }

    public function isProtected(): bool
    {
        return $this->property->isProtected();
    }

    public function isPrivate(): bool
    {
        return $this->property->isPrivate();
    }

    public function isPromoted(): bool
    {
        return $this->property->isPromoted();
    }

    public function isStatic(): bool
    {
        return $this->property->isStatic();
    }

    public function isReadOnly(): bool
    {
        return $this->property->isReadOnly();
    }

    public function isDefault(): bool
    {
        return $this->property->isDefault();
    }

    public function isDynamic(): bool
    {
        return !$this->isDefault();
    }

    public function docComment(): string
    {
        return $this->property->getDocComment();
    }

    public function defaultValue(): mixed
    {
        return $this->property->getDefaultValue();
    }

    public function hasDefaultValue(): bool
    {
        return $this->property->hasDefaultValue();
    }
}

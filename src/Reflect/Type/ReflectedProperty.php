<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect\Type;

use Closure;
use ReflectionAttribute;
use ReflectionProperty;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use function Psl\Vec\map;

final class ReflectedProperty
{
    public function __construct(
        private readonly ReflectionProperty $property
    ) {
    }

    public function name(): string
    {
        return $this->property->getName();
    }

    public function declaringClass(): ReflectedClass
    {
        return new ReflectedClass($this->property->getDeclaringClass());
    }

    public function visibility(): Visibility
    {
        return Visibility::forProperty($this->property);
    }

    /**
     * @param Closure(ReflectedProperty): bool $predicate
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

    /**
     * @template T extends object
     *
     * @param class-string<T>|null $attributeClassName
     *
     * @return (T is null ? list<object> : list<T>)
     * @throws UnreflectableException
     */
    public function attributes(?string $attributeClassName = null): array
    {
        return map(
            $this->property->getAttributes($attributeClassName, ReflectionAttribute::IS_INSTANCEOF),
            static fn (ReflectionAttribute $attribute): object => (new ReflectedAttribute($attribute))->instantiate()
        );
    }

    /**
     * @param class-string $attributeClassName
     */
    public function hasAttributeOfType(string $attributeClassName): bool
    {
        return (bool) $this->property->getAttributes($attributeClassName, ReflectionAttribute::IS_INSTANCEOF);
    }

    /**
     * @template T
     * @param Closure(ReflectionProperty): T $closure
     */
    public function apply(Closure $closure): mixed
    {
        return $closure($this->property);
    }
}

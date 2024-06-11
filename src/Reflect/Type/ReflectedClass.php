<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect\Type;

use AllowDynamicProperties;
use Closure;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;
use Throwable;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use function Psl\Dict\filter;
use function Psl\Dict\pull;
use function Psl\Vec\map;

final class ReflectedClass
{
    public function __construct(
        private readonly ReflectionClass $class
    ) {
    }

    /**
     * @param class-string $className
     */
    public static function fromFullyQualifiedClassName(string $className): self
    {
        try {
            return new self(new ReflectionClass($className));
        } catch (Throwable $previous) {
            throw UnreflectableException::unknownClass($className, $previous);
        }
    }

    public static function fromObject(object $object): self
    {
        return new self(new ReflectionObject($object));
    }

    /**
     * @param class-string|object $objectOrClassName
     * @throws UnreflectableException
     */
    public static function from(mixed $objectOrClassName): self
    {
        return is_string($objectOrClassName)
            ? self::fromFullyQualifiedClassName($objectOrClassName)
            : self::fromObject($objectOrClassName);
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
     * @param Closure(ReflectedClass): bool $predicate
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

    public function isDynamic(): bool
    {
        // Dynamic props is a 80200 feature.
        // IN previous versions, all objects are dynamic (without any warning).
        if (PHP_VERSION_ID < 80200) {
            return true;
        }

        return $this->hasAttributeOfType(AllowDynamicProperties::class);
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

    public function property(string $property): ReflectedProperty
    {
        try {
            return new ReflectedProperty($this->class->getProperty($property));
        } catch (Throwable $previous) {
            throw UnreflectableException::unknownProperty(
                $this->fullName(),
                $property,
                $previous
            );
        }
    }

    /**
     * @param null|Closure(ReflectedProperty): bool $predicate
     *
     * @return array<string, ReflectedProperty>
     *
     * @throws UnreflectableException
     */
    public function properties(Closure|null $predicate = null): array
    {
        $properties = pull(
            $this->class->getProperties(),
            static fn (ReflectionProperty $reflectionProperty): ReflectedProperty => new ReflectedProperty($reflectionProperty),
            static fn (ReflectionProperty $reflectionProperty): string => $reflectionProperty->name
        );

        if ($predicate !== null) {
            return filter($properties, $predicate);
        }

        return $properties;
    }

    /**
     * @template Ta extends object
     *
     * @param class-string<Ta>|null $attributeClassName
     *
     * @return (Ta is null ? list<object> : list<Ta>)
     * @throws UnreflectableException
     */
    public function attributes(?string $attributeClassName = null): array
    {
        return map(
            $this->class->getAttributes($attributeClassName, ReflectionAttribute::IS_INSTANCEOF),
            static fn (ReflectionAttribute $attribute): object => (new ReflectedAttribute($attribute))->instantiate()
        );
    }

    /**
     * @param class-string $attributeClassName
     */
    public function hasAttributeOfType(string $attributeClassName): bool
    {
        return (bool) $this->class->getAttributes($attributeClassName, ReflectionAttribute::IS_INSTANCEOF);
    }

    /**
     * @throws UnreflectableException
     */
    public function instantiate(): object
    {
        try {
            return $this->class->newInstanceWithoutConstructor();
        } catch (Throwable $previous) {
            throw UnreflectableException::nonInstantiatable($this->fullName(), $previous);
        }
    }

    /**
     * @template T
     * @param Closure(ReflectionClass): T $closure
     */
    public function apply(Closure $closure): mixed
    {
        return $closure($this->class);
    }
}

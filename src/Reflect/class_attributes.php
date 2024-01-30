<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use ReflectionAttribute;
use Throwable;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use function Psl\Result\wrap;
use function Psl\Vec\map;

/**
 * @template T extends object
 *
 * @param class-string $className
 * @param class-string<T>|null $attributeClassName
 * @return (T is null ? list<object> : list<T>)
 *
 * @throws UnreflectableException
 */
function class_attributes(string $className, ?string $attributeClassName = null): array
{
    $propertyInfo = reflect_class($className);

    return map(
        $propertyInfo->getAttributes($attributeClassName, ReflectionAttribute::IS_INSTANCEOF),
        static fn (ReflectionAttribute $attribute): object => wrap(static fn () => $attribute->newInstance())
            ->catch(
                static fn (Throwable $error) => throw UnreflectableException::nonInstantiatable(
                    $attribute->getName(),
                    $error
                )
            )
            ->getResult()
    );
}

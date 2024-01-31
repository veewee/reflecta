<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect\Internal;

use ReflectionAttribute;
use Throwable;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use function Psl\Result\wrap;
use function Psl\Vec\map;

/**
 * @psalm-internal VeeWee\Reflecta
 *
 * @template T extends object
 *
 * @param object|class-string $objectOrClassName
 * @param class-string<T>|null $attributeClassName
 *
 * @return (T is null ? list<object> : list<T>)
 * @throws UnreflectableException
 */
function reflect_class_attributes(mixed $objectOrClassName, ?string $attributeClassName = null): array
{
    $reflection = is_string($objectOrClassName) ? reflect_class($objectOrClassName) : reflect_object($objectOrClassName);

    return map(
        $reflection->getAttributes($attributeClassName, ReflectionAttribute::IS_INSTANCEOF),
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

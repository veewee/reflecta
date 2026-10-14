<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

use ReflectionProperty;
use Throwable;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;
use function is_object;
use function method_exists;
use function property_exists;

/**
 * @template S of object
 * @return Lens<S|null, S|null, mixed, mixed>
 * @psalm-pure
 */
function optional_property(string $propertyName): Lens
{
    /** @var Lens<S|null, S|null, mixed, mixed> $optional */
    $optional = optional(property($propertyName));
    /** @var array<class-string, ReflectionProperty|false> $declared */
    $declared = [];
    $resolve =
        /**
         * @param class-string $className
         */
        static function (string $className) use ($propertyName): ReflectionProperty|false {
            /** @var ReflectionProperty|null $property */
            $property = ReflectedClass::fromFullyQualifiedClassName($className)
                ->findProperty($propertyName)
                ?->apply(static fn (ReflectionProperty $property): ReflectionProperty => $property);

            return $property ?? false;
        };

    return new Lens(
        /**
         * @param S|null $subject
         */
        static function (mixed $subject) use ($propertyName, $resolve, &$declared): mixed {
            if (!is_object($subject)) {
                return null;
            }

            // Resolved from the class, not the object, so one instance's dynamic properties never enter the cache.
            $property = $declared[$subject::class] ??= $resolve($subject::class);

            if ($property === false) {
                return property_exists($subject, $propertyName) ? $subject->{$propertyName} : null;
            }

            // An unset property falls back to __get, so only skip the read when there is none.
            if (!$property->isInitialized($subject) && !method_exists($subject, '__get')) {
                return null;
            }

            try {
                return $property->getValue($subject);
            } catch (Throwable) {
                return null;
            }
        },
        /**
         * @param S|null $subject
         * @return S|null
         */
        static fn (mixed $subject, mixed $value): ?object => $optional->set($subject, $value),
    );
}

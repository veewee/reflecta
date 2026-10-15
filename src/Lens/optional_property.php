<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

use ReflectionProperty;
use Throwable;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;
use function is_object;
use function method_exists;

/**
 * @template S of object
 * @return Lens<S|null, S|null, mixed, mixed>
 * @psalm-pure
 */
function optional_property(string $propertyName): Lens
{
    /** @var Lens<S|null, S|null, mixed, mixed> $optional */
    $optional = optional(property($propertyName));

    return new Lens(
        /**
         * @param S|null $subject
         */
        static function (mixed $subject) use ($propertyName): mixed {
            if (!is_object($subject)) {
                return null;
            }

            try {
                return ReflectedClass::fromObject($subject)->findProperty($propertyName)?->apply(
                    static function (ReflectionProperty $property) use ($subject): mixed {
                        // An unset property falls back to __get, so only skip the read when there is none.
                        if (!$property->isInitialized($subject) && !method_exists($subject, '__get')) {
                            return null;
                        }

                        return $property->getValue($subject);
                    }
                );
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

<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

use Throwable;
use function is_object;
use function method_exists;
use function property_exists;
use function VeeWee\Reflecta\Reflect\class_find_property;

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
                // PHP resolves $subject->{$name} as: the class itself, then a dynamic property, then the parents.
                // Dynamic properties are public, unhooked and always initialised, so they are read directly.
                $className = $subject::class;
                if (!property_exists($className, $propertyName) && property_exists($subject, $propertyName)) {
                    return $subject->{$propertyName};
                }

                $property = class_find_property($className, $propertyName);
                if ($property === null) {
                    return null;
                }

                // An unset property falls back to __get, so only skip the read when there is none.
                if (!$property->isInitialized($subject) && !method_exists($subject, '__get')) {
                    return null;
                }

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

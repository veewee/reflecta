<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

use function is_array;

/**
 * @param array-key $index
 * @return Lens<array|null, array|null, mixed, mixed>
 * @psalm-pure
 */
function optional_index(string|int $index): Lens
{
    $optional = optional(index($index));

    /** @return Lens<array|null, array|null, mixed, mixed> */
    return new Lens(
        /**
         * @param array|null $subject
         */
        static fn (mixed $subject): mixed => is_array($subject) ? ($subject[$index] ?? null) : null,
        /**
         * @param array|null $subject
         */
        static fn (mixed $subject, mixed $value): ?array => $optional->set($subject, $value),
    );
}

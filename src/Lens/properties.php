<?php

namespace VeeWee\Reflecta\Lens;

use function VeeWee\Reflecta\Reflect\properties_get;
use function VeeWee\Reflecta\Reflect\properties_set;

/**
 * @template S of object
 * @return Lens<S, array<string, mixed>>
 * @psalm-pure
 */
function properties(): Lens {
    return new Lens(
        /**
         * @param S $subject
         * @return array<string, mixed>
         */
        static fn (object $subject): array => properties_get($subject),
        /**
         * @param S $subject
         * @param array<string, mixed> $value
         * @return S
         */
        static fn (object $subject, array $value): object => properties_set($subject, $value),
    );
}

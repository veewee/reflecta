<?php

namespace VeeWee\Reflecta\Lens;

use function VeeWee\Reflecta\ArrayAccess\index_get;
use function VeeWee\Reflecta\ArrayAccess\index_set;

/**
 * @template S of array
 * @template A
 * @param array-key $index
 * @return Lens<S, A>
 * @psalm-pure
 */
function index($index): Lens {
    /** @return Lens<S, A> */
    return new Lens(
        /**
         * @param S $subject
         * @return A
         *
         * @psalm-suppress MixedInferredReturnType, MixedReturnStatement
         */
        static fn (array $subject): mixed => index_get($subject, $index),
        /**
         * @param S $subject
         * @param A $value
         * @return S
         *
         * @psalm-suppress InvalidReturnType, InvalidReturnStatement
         */
        static fn (array $subject, mixed $value): array => index_set($subject, $index, $value),
    );
}

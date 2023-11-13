<?php

namespace VeeWee\Reflecta\Reflect;

use Psl\Option\Option;
use VeeWee\Reflecta\Optic\Lens;
use function Psl\Option\from_nullable;

/**
 * @template S
 *
 * @return Lens<S, Option<S>>
 *
 * @psalm-pure
 */
function some(): Lens {
    return new Lens(
        /**
         * @param S $subject
         * @return Option<S>
         */
        static fn ($subject): Option => from_nullable($subject),
        /**
         * @param S $subject
         * @param Option<S> $value
         * @return S
         */
        static fn ($subject, Option $value) => $value->unwrapOr(null),
    );
}

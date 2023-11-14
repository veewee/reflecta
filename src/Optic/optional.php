<?php

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Optic\Lens;
use function Psl\Result\wrap;

/**
 * @template S
 * @template A
 *
 * @param Lens<S, A> $that
 *
 * @return Lens<S, A|null>
 *
 * @psalm-pure
 */
function optional(Lens $that): Lens {
    return new Lens(
        /**
         * @param S|null $subject
         * @return A|null
         */
        static fn ($subject) => $that->tryGet($subject)->proceed(
            /**
             * @param A $a
             * @return A
             */
            fn ($a) => $a,
            /**
             * @return null
             */
            fn () => null
        ),
        /**
         * @param S|null $subject
         * @param A $value
         * @return S|null
         */
        static fn ($subject, $value) => $that->trySet($subject, $value)->proceed(
            /**
             * @param S $s
             * @return S
             */
            fn ($s) => $s,
            /**
             * @return null
             */
            fn () => null
        ),
    );
}

<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

/**
 * @template S
 * @template T
 * @template A
 * @template B
 *
 * @param LensInterface<S, T, A, B> $that
 *
 * @return Lens<S|null, T|null, A|null, B|null>
 *
 * @psalm-pure
 */
function optional(LensInterface $that): Lens
{
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
            static fn ($a) => $a,
            /**
             * @return null
             */
            static fn () => null
        ),
        /**
         * @param S|null $subject
         * @param B|null $value
         * @return T|null
         */
        static fn ($subject, $value) => $that->trySet($subject, $value)->proceed(
            /**
             * @param T $s
             * @return T
             */
            static fn ($s) => $s,
            /**
             * @return null
             */
            static fn () => null
        ),
    );
}

<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

/**
 * @template S
 * @template A
 *
 * @param LensInterface<S, A> $that
 *
 * @return Lens<S, A|null>
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
         * @param A $value
         * @return S|null
         */
        static fn ($subject, $value) => $that->trySet($subject, $value)->proceed(
            /**
             * @param S $s
             * @return S
             */
            static fn ($s) => $s,
            /**
             * @return null
             */
            static fn () => null
        ),
    );
}

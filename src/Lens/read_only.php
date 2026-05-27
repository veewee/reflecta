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
 * @return Lens<S, S, A, A>
 *
 * @psalm-pure
 */
function read_only(LensInterface $that): Lens
{
    return Lens::readonly(
        /**
         * @param S $subject
         * @return A
         */
        static fn ($subject) => $that->get($subject)
    );
}

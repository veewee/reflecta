<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

/**
 * @template S
 * @template A
 *
 * @param LensInterface<S, A> $that
 *
 * @return Lens<S, A>
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

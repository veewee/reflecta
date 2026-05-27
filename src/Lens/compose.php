<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

use function Psl\Iter\reduce;

/**
 * @no-named-arguments
 *
 * @template S
 * @template T
 * @template A
 * @template B
 *
 * @param array<int, LensInterface<mixed, mixed, mixed, mixed>> $lenses
 *
 * @return LensInterface<S, T, A, B>
 *
 * @psalm-pure
 * @psalm-suppress ImpureFunctionCall
 */
function compose(LensInterface ... $lenses): LensInterface
{
    /** @var LensInterface<S, T, A, B> */
    return reduce(
        $lenses,
        static fn (LensInterface $current, LensInterface $next) => $current->compose($next),
        Lens::identity()
    );
}

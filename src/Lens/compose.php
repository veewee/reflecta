<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

use function Psl\Iter\reduce;

/**
 * @no-named-arguments
 *
 * @template S
 * @template A
 *
 * @param non-empty-array<int, LensInterface<mixed, mixed>> $lenses
 *
 * @return LensInterface<S, A>
 *
 * @psalm-pure
 * @psalm-suppress ImpureFunctionCall
 */
function compose(LensInterface ... $lenses): LensInterface
{
    /** @var LensInterface<S, A> */
    return reduce(
        $lenses,
        static fn (LensInterface $current, LensInterface $next) => $current->compose($next),
        Lens::identity()
    );
}

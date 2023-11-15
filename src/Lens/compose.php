<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

use function Psl\Iter\reduce;

/**
 * @no-named-arguments
 *
 * @template S
 * @template A
 *
 * @param non-empty-array<int, Lens<mixed, mixed>> $lenses
 *
 * @return Lens<S, A>
 *
 * @psalm-pure
 * @psalm-suppress ImpureFunctionCall
 */
function compose(Lens ... $lenses): Lens
{
    /** @Lens<S, A> */
    return reduce(
        $lenses,
        static fn (Lens $current, Lens $next) => $current->compose($next),
        Lens::identity()
    );
}

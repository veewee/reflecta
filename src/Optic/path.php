<?php

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Optic\Lens;
use function Psl\Iter\reduce;

/**
 * @no-named-arguments
 *
 * @template S
 * @template A
 *
 * @param non-empty-array<int, Lens<mixed, mixed>> $other
 *
 * @return Lens<S, A>
 *
 * @psalm-pure
 * @psalm-suppress ImpureFunctionCall
 */
function path(Lens ... $lenses): Lens {
    /** @Lens<S, A> */
    return reduce(
        $lenses,
        fn (Lens $current, Lens $next) => $current->compose($next),
        Lens::id()
    );
}

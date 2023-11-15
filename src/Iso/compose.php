<?php

namespace VeeWee\Reflecta\Iso;

use function Psl\Iter\reduce;

/**
 * @no-named-arguments
 *
 * @template S
 * @template A
 *
 * @param non-empty-array<int, Iso<mixed, mixed>> $other
 *
 * @return Iso<S, A>
 *
 * @psalm-pure
 * @psalm-suppress ImpureFunctionCall
 */
function compose(Iso ... $isos): Iso {
    /** @Iso<S, A> */
    return reduce(
        $isos,
        fn (Iso $current, Iso $next) => $current->compose($next),
        Iso::identity()
    );
}

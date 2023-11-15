<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Iso;

use function Psl\Iter\reduce;

/**
 * @no-named-arguments
 *
 * @template S
 * @template A
 *
 * @param non-empty-array<int, Iso<mixed, mixed>> $isos
 *
 * @return Iso<S, A>
 *
 * @psalm-pure
 * @psalm-suppress ImpureFunctionCall
 */
function compose(Iso ... $isos): Iso
{
    /** @Iso<S, A> */
    return reduce(
        $isos,
        static fn (Iso $current, Iso $next) => $current->compose($next),
        Iso::identity()
    );
}

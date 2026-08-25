<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Iso;

use function Psl\Iter\reduce;

/**
 * @no-named-arguments
 *
 * @template S
 * @template T
 * @template A
 * @template B
 *
 * @param array<int, IsoInterface<mixed, mixed, mixed, mixed>> $isos
 *
 * @return IsoInterface<S, T, A, B>
 *
 * @psalm-pure
 * @psalm-suppress ImpureFunctionCall
 */
function compose(IsoInterface ... $isos): IsoInterface
{
    return reduce(
        $isos,
        static fn (IsoInterface $current, IsoInterface $next) => $current->compose($next),
        Iso::identity()
    );
}

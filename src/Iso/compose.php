<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Iso;

use function Psl\Iter\reduce;

/**
 * @no-named-arguments
 *
 * @template S
 * @template A
 *
 * @param non-empty-array<int, IsoInterface<mixed, mixed>> $isos
 *
 * @return IsoInterface<S, A>
 *
 * @psalm-pure
 * @psalm-suppress ImpureFunctionCall
 */
function compose(IsoInterface ... $isos): IsoInterface
{
    /** @var IsoInterface<S, A> */
    return reduce(
        $isos,
        static fn (IsoInterface $current, IsoInterface $next) => $current->compose($next),
        Iso::identity()
    );
}

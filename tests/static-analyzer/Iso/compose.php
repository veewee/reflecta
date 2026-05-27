<?php declare(strict_types=1);

namespace VeeWee\Reflecta\SaTests\Iso;

use VeeWee\Reflecta\Iso\Iso;
use function VeeWee\Reflecta\Iso\compose;

/**
 * @template A1
 * @template A2
 * @template B1
 * @template B2
 * @template C1
 * @template C2
 * @template D1
 * @template D2
 *
 * @param Iso<A1, A2, B1, B2> $iso1
 * @param Iso<B1, B2, C1, C2> $iso2
 * @param Iso<C1, C2, D1, D2> $iso3
 * @return Iso<A1, A2, D1, D2>
 */
function it_knows_composed_result(Iso $iso1, Iso $iso2, Iso $iso3): Iso
{
    return compose($iso1, $iso2, $iso3);
}

/**
 * Boundary between iso1 and iso2 doesn't line up:
 * iso1 outputs (B1, B2) on its (A, B) side, but iso2's (S, T) side is (C1, C2).
 * With invariant templates Psalm catches the mismatch via standard inference.
 *
 * @template A1
 * @template A2
 * @template B1
 * @template B2
 * @template C1
 * @template C2
 * @template D1
 * @template D2
 *
 * @param Iso<A1, A2, B1, B2> $iso1
 * @param Iso<C1, C2, C1, C2> $iso2
 * @param Iso<C1, C2, D1, D2> $iso3
 * @return Iso<A1, A2, D1, D2>
 *
 * @psalm-suppress InvalidArgument
 */
function it_knows_broken_composition(Iso $iso1, Iso $iso2, Iso $iso3): Iso
{
    return compose($iso1, $iso2, $iso3);
}

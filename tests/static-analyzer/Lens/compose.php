<?php declare(strict_types=1);

namespace VeeWee\Reflecta\SaTests\Lens;

use VeeWee\Reflecta\Lens\Lens;
use function VeeWee\Reflecta\Lens\compose;

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
 * @param Lens<A1, A2, B1, B2> $lens1
 * @param Lens<B1, B2, C1, C2> $lens2
 * @param Lens<C1, C2, D1, D2> $lens3
 * @return Lens<A1, A2, D1, D2>
 */
function it_knows_composed_result(Lens $lens1, Lens $lens2, Lens $lens3): Lens
{
    return compose($lens1, $lens2, $lens3);
}

/**
 * Boundary between lens1 and lens2 doesn't line up:
 * lens1 outputs (B1, B2) on its (A, B) side, but lens2's (S, T) side is (C1, C2).
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
 * @param Lens<A1, A2, B1, B2> $lens1
 * @param Lens<C1, C2, C1, C2> $lens2
 * @param Lens<C1, C2, D1, D2> $lens3
 * @return Lens<A1, A2, D1, D2>
 *
 * Note that this case only asserts anything under psalm. Mago cannot detect the
 * mismatch: that needs the argument side, and `CallableSignatureProvider` runs
 * before arguments are analyzed.
 *
 * @psalm-suppress InvalidArgument
 */
function it_knows_broken_composition(Lens $lens1, Lens $lens2, Lens $lens3): Lens
{
    return compose($lens1, $lens2, $lens3);
}

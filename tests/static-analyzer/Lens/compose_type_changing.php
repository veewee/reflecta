<?php declare(strict_types=1);

namespace VeeWee\Reflecta\SaTests\Lens;

use VeeWee\Reflecta\Lens\Lens;
use function VeeWee\Reflecta\Lens\compose;

/**
 * Type-changing compose: S, T, A, B can all differ.
 * lens1: (S1, T1, A1, B1)
 * lens2: (A1, B1, A2, B2)  ← shares (A, B) of lens1 as its (S, T)
 * compose result: (S1, T1, A2, B2)
 *
 * @template S1
 * @template T1
 * @template A1
 * @template B1
 * @template A2
 * @template B2
 *
 * @param Lens<S1, T1, A1, B1> $lens1
 * @param Lens<A1, B1, A2, B2> $lens2
 * @return Lens<S1, T1, A2, B2>
 */
function it_composes_type_changing_lenses(Lens $lens1, Lens $lens2): Lens
{
    return compose($lens1, $lens2);
}

<?php declare(strict_types=1);

namespace VeeWee\Reflecta\SaTests\Iso;

use VeeWee\Reflecta\Iso\Iso;
use function VeeWee\Reflecta\Iso\compose;

/**
 * Type-changing compose: S, T, A, B can all differ.
 * iso1: (S1, T1, A1, B1)
 * iso2: (A1, B1, A2, B2)  ← shares (A, B) of iso1 as its (S, T)
 * compose result: (S1, T1, A2, B2)
 *
 * @template S1
 * @template T1
 * @template A1
 * @template B1
 * @template A2
 * @template B2
 *
 * @param Iso<S1, T1, A1, B1> $iso1
 * @param Iso<A1, B1, A2, B2> $iso2
 * @return Iso<S1, T1, A2, B2>
 */
function it_composes_type_changing_isos(Iso $iso1, Iso $iso2): Iso
{
    return compose($iso1, $iso2);
}

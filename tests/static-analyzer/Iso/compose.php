<?php declare(strict_types=1);

namespace VeeWee\Reflecta\SaTests\Iso;

use VeeWee\Reflecta\Iso\Iso;
use function VeeWee\Reflecta\Iso\compose;

/**
 * @template A
 * @template B
 * @template C
 * @template D
 *
 * @param Iso<A,B> $iso1
 * @param Iso<B,C> $iso2
 * @param Iso<C,D> $iso3
 * @return Iso<A,D>
 */
function it_knows_composed_result(Iso $iso1, Iso $iso2, Iso $iso3): Iso
{
    return compose($iso1, $iso2, $iso3);
}

/**
 * @template A
 * @template B
 * @template C
 * @template D
 *
 * @param Iso<A,B> $iso1
 * @param Iso<C,C> $iso2
 * @param Iso<C,D> $iso3
 * @return Iso<A,D>
 *
 * @  psalm-suppress InvalidArgument -
 * TODO : Invalid compose iso-param detection does not work any since contravariant templates are introduced.
 */
function it_knows_broken_composition(Iso $iso1, Iso $iso2, Iso $iso3): Iso
{
    return compose($iso1, $iso2, $iso3);
}

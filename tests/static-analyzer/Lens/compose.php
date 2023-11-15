<?php declare(strict_types=1);

namespace VeeWee\Reflecta\SaTests\Lens;

use VeeWee\Reflecta\Lens\Lens;
use function VeeWee\Reflecta\Lens\compose;

/**
 * @template A
 * @template B
 * @template C
 * @template D
 *
 * @param Lens<A,B> $lens1
 * @param Lens<B,C> $lens2
 * @param Lens<C,D> $lens3
 * @return Lens<A,D>
 */
function it_knows_composed_result(Lens $lens1, Lens $lens2, Lens $lens3): Lens
{
    return compose($lens1, $lens2, $lens3);
}

/**
 * @template A
 * @template B
 * @template C
 * @template D
 *
 * @param Lens<A,B> $lens1
 * @param Lens<C,C> $lens2
 * @param Lens<C,D> $lens3
 * @return Lens<A,D>
 *
 * @psalm-suppress InvalidArgument
 */
function it_knows_broken_composition(Lens $lens1, Lens $lens2, Lens $lens3): Lens
{
    return compose($lens1, $lens2, $lens3);
}

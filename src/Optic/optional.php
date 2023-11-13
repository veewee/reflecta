<?php

namespace VeeWee\Reflecta\Reflect;

use Psl\Option\Option;
use VeeWee\Reflecta\Optic\Lens;

/**
 * @template S
 * @template A
 *
 * @return Lens<S, Option<A>>
 *
 * @psalm-pure
 */
function optional(Lens $that): Lens {
    /** @var Lens<S, Option<A>> */
    return $that->compose(some());
}

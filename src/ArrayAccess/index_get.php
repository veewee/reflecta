<?php

namespace VeeWee\Reflecta\ArrayAccess;


use VeeWee\Reflecta\ArrayAccess\Exception\ArrayAccessException;

/**
 * @pure
 *
 * @template Tk of array-key
 * @template Tv
 *
 * @param array<Tk, Tv> $array
 * @param Tk $index
 * @return Tv
 *
 * @throws ArrayAccessException
 */
function index_get(array $array, $index): mixed
{
    if (!array_key_exists($index, $array)) {
        throw ArrayAccessException::cannotAccessIndex($index);
    }

    return $array[$index];
}

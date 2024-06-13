<?php declare(strict_types=1);

namespace VeeWee\Reflecta\ArrayAccess;

use VeeWee\Reflecta\ArrayAccess\Exception\ArrayAccessException;
use function array_key_exists;

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
function index_get(array $array, string|int $index): mixed
{
    if (!array_key_exists($index, $array)) {
        throw ArrayAccessException::cannotAccessIndex($index);
    }

    return $array[$index];
}

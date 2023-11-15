<?php declare(strict_types=1);

namespace VeeWee\Reflecta\ArrayAccess;

/**
 * @pure
 *
 * @template Tk of array-key
 * @template Tv
 *
 * @param array<Tk, Tv> $array
 * @param Tk $index
 * @param Tv $value
 *
 * @return array<Tk, Tv>
 */
function index_set(array $array, $index, $value): array
{
    $new = array_merge($array, []);
    $new[$index] = $value;

    return $new;
}

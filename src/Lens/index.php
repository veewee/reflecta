<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

use function VeeWee\Reflecta\ArrayAccess\index_get;
use function VeeWee\Reflecta\ArrayAccess\index_set;

/**
 * @param array-key $index
 * @return Lens<array, mixed>
 * @psalm-pure
 */
function index($index): Lens
{
    /** @return Lens<array, mixed> */
    return new Lens(
        static fn (array $subject): mixed => index_get($subject, $index),
        static fn (array $subject, mixed $value): array => index_set($subject, $index, $value),
    );
}

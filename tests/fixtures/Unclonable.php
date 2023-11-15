<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\TestFixtures;

use RuntimeException;

final class Unclonable
{
    public ?int $z = 0;

    public function __clone(): void
    {
        throw new RuntimeException('no clones allowed');
    }
}

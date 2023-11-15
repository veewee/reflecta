<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\TestFixtures;

class Unclonable extends X
{
    public function __clone(): void
    {
        throw new \RuntimeException('no clones allowed');
    }
}

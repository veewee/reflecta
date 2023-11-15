<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\TestFixtures;

class ReadonlyX
{
    public function __construct(
        public readonly int $z
    ) {
    }
}

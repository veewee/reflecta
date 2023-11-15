<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\TestFixtures;

use RuntimeException;

final class Boom
{
    public function __construct()
    {
        throw new RuntimeException('boom');
    }
}

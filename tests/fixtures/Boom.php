<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\TestFixtures;

class Boom
{
    public function __construct()
    {
        throw new \RuntimeException('boom');
    }
}

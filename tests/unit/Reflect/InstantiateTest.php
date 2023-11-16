<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use Generator;
use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\TestFixtures\Boom;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\instantiate;

final class InstantiateTest extends TestCase
{

    public function test_it_errors(): void
    {
        $this->expectException(UnreflectableException::class);
        instantiate(Generator::class);
    }

    public function it_returns_instance()
    {
        $x = instantiate(X::class);

        static::assertInstanceOf(X::class, $x);
    }

    public function it_skips_constructor()
    {
        $boom = instantiate(Boom::class);

        static::assertInstanceOf(Boom::class, $boom);
    }
}

<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\TestFixtures\Boom;
use VeeWee\Reflecta\TestFixtures\X;
use function VeeWee\Reflecta\Reflect\instantiate;

class InstantiateTest extends TestCase
{

    /** @test */
    public function it_errors(): void
    {
        $this->markTestIncomplete('TODO : find final internal class');
        $this->expectException(UnreflectableException::class);
        instantiate('internalfinalclass');
    }

    public function it_returns_instance()
    {
        $x = instantiate(X::class);

        self::assertInstanceOf(X::class, $x);
    }

    public function it_skips_constructor()
    {
        $boom = instantiate(Boom::class);

        self::assertInstanceOf(Boom::class, $boom);
    }
}

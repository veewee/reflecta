<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Lens;

use PHPUnit\Framework\TestCase;
use function VeeWee\Reflecta\Lens\property;

class PropertyTest extends TestCase
{
    /** @test */
    public function it_can_work_with_property(): void
    {
        $lens = property('hello');
        $data = (object)['hello' => 'world'];

        self::assertSame('world', $lens->get($data));
        self::assertSame('world', $lens->tryGet($data)->getResult());
        self::assertTrue($lens->tryGet((object)[])->isFailed());

        self::assertEquals((object) ['hello' => 'earth'], $lens->set($data, 'earth'));
        self::assertNotSame($data, $lens->set($data, 'earth'));
        self::assertEquals((object) ['hello' => 'earth'], $lens->trySet($data, 'earth')->getResult());
        self::assertTrue($lens->trySet((object)[], 'earth')->isFailed());
    }
}

<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Lens;

use PHPUnit\Framework\TestCase;
use function VeeWee\Reflecta\Lens\properties;

class PropertiesTest extends TestCase
{
    /** @test */
    public function it_can_work_with_properties(): void
    {
        $lens = properties();
        $expected = ['hello' => 'world'];
        $data = (object)$expected;

        self::assertSame($expected, $lens->get($data));
        self::assertSame($expected, $lens->tryGet($data)->getResult());
        self::assertTrue($lens->tryGet(null)->isFailed());

        self::assertEquals((object)['hello' => 'earth'], $lens->set($data, ['hello' => 'earth']));
        self::assertNotSame($data, $lens->set($data, ['hello' => 'earth']));
        self::assertEquals((object)['hello' => 'earth'], $lens->trySet($data, ['hello' => 'earth'])->getResult());
        self::assertTrue($lens->trySet(null, 'earth')->isFailed());
    }
}

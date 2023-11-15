<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Lens;

use PHPUnit\Framework\TestCase;
use function VeeWee\Reflecta\Lens\property;

final class PropertyTest extends TestCase
{
    
    public function test_it_can_work_with_property(): void
    {
        $lens = property('hello');
        $data = (object)['hello' => 'world'];

        static::assertSame('world', $lens->get($data));
        static::assertSame('world', $lens->tryGet($data)->getResult());
        static::assertTrue($lens->tryGet((object)[])->isFailed());

        static::assertEquals((object) ['hello' => 'earth'], $lens->set($data, 'earth'));
        static::assertNotSame($data, $lens->set($data, 'earth'));
        static::assertEquals((object) ['hello' => 'earth'], $lens->trySet($data, 'earth')->getResult());
        static::assertTrue($lens->trySet((object)[], 'earth')->isFailed());
    }
}

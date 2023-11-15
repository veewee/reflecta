<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Lens;

use PHPUnit\Framework\TestCase;
use function VeeWee\Reflecta\Lens\index;

final class IndexTest extends TestCase
{
    
    public function test_it_can_work_with_index_data(): void
    {
        $lens = index('hello');
        $data = ['hello' => 'world'];

        static::assertSame('world', $lens->get($data));
        static::assertSame('world', $lens->tryGet($data)->getResult());
        static::assertTrue($lens->tryGet([])->isFailed());

        static::assertSame(['hello' => 'earth'], $lens->set($data, 'earth'));
        static::assertSame(['hello' => 'earth'], $lens->trySet($data, 'earth')->getResult());
        static::assertSame(['hello' => 'earth'], $lens->trySet([], 'earth')->getResult());
    }
}

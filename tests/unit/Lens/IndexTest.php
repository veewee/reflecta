<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Lens;

use PHPUnit\Framework\TestCase;
use function VeeWee\Reflecta\Lens\index;

class IndexTest extends TestCase
{
    /** @test */
    public function it_can_work_with_index_data(): void
    {
        $lens = index('hello');
        $data = ['hello' => 'world'];

        self::assertSame('world', $lens->get($data));
        self::assertSame('world', $lens->tryGet($data)->getResult());
        self::assertTrue($lens->tryGet([])->isFailed());

        self::assertSame(['hello' => 'earth'], $lens->set($data, 'earth'));
        self::assertSame(['hello' => 'earth'], $lens->trySet($data, 'earth')->getResult());
        self::assertSame(['hello' => 'earth'], $lens->trySet([], 'earth')->getResult());
    }
}

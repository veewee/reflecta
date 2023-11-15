<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\ArrayAccess;

use PHPUnit\Framework\TestCase;
use function VeeWee\Reflecta\ArrayAccess\index_set;

final class IndexSetTest extends TestCase
{
    public static function provideSetCases()
    {
        yield 'vec' => [
            [1, 2, 3],
            0,
            100,
            [100, 2, 3]
        ];
        yield 'dict' => [
            ['hello' => 'world'],
            'hello',
            'earth',
            ['hello' => 'earth']
        ];
        yield 'unkown' => [
            ['hello' => 'world'],
            'unkown',
            'unkown',
            ['hello' => 'world', 'unkown' => 'unkown']
        ];
    }

    /**
     *
     * @dataProvider provideSetCases
     */
    public function test_it_can_set_in_array(
        array $data,
        string|int $index,
        mixed $value,
        array $expected
    ): void {
        $actual = index_set($data, $index, $value);
        static::assertSame($expected, $actual);
    }
}

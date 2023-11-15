<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\ArrayAccess;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\ArrayAccess\Exception\ArrayAccessException;
use function VeeWee\Reflecta\ArrayAccess\index_get;

final class IndexGetTest extends TestCase
{
    public static function provideGetCases()
    {
        yield 'vec' => [
            [1, 2, 3],
            0,
            1
        ];
        yield 'dict' => [
            ['hello' => 'world'],
            'hello',
            'world'
        ];
    }

    /**
     *
     * @dataProvider provideGetCases
     */
    public function test_it_can_get_from_array(
        array $data,
        string|int $index,
        mixed $expected
    ): void {
        $actual = index_get($data, $index);
        static::assertSame($expected, $actual);
    }

    
    public function test_it_can_not_get_from_array(): void
    {
        $this->expectException(ArrayAccessException::class);
        index_get([], 'invalid');
    }
}

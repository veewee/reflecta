<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\ArrayAccess;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\ArrayAccess\Exception\ArrayAccessException;
use function VeeWee\Reflecta\ArrayAccess\index_get;

class IndexGetTest extends TestCase
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
     * @test
     * @dataProvider provideGetCases
     */
    public function it_can_get_from_array(
        array $data,
        string|int $index,
        mixed $expected
    ): void {
        $actual = index_get($data, $index);
        self::assertSame($expected, $actual);
    }

    /** @test */
    public function it_can_not_get_from_array(): void
    {
        $this->expectException(ArrayAccessException::class);
        index_get([], 'invalid');
    }
}

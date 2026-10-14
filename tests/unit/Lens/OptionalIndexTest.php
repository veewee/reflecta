<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Lens;

use ArrayObject;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function VeeWee\Reflecta\Lens\index;
use function VeeWee\Reflecta\Lens\optional;
use function VeeWee\Reflecta\Lens\optional_index;

final class OptionalIndexTest extends TestCase
{
    #[DataProvider('provideSubjects')]
    public function test_it_gets_like_the_optional_index_composition(mixed $subject, string|int $index, mixed $expected): void
    {
        $actual = optional_index($index)->get($subject);

        static::assertSame($expected, $actual);
        static::assertSame(optional(index($index))->get($subject), $actual);
    }

    #[DataProvider('provideSetSubjects')]
    public function test_it_sets_like_the_optional_index_composition(mixed $subject, string|int $index, mixed $expected): void
    {
        $actual = optional_index($index)->set($subject, 'new');

        static::assertSame($expected, $actual);
        static::assertSame(optional(index($index))->set($subject, 'new'), $actual);
    }

    public static function provideSubjects(): iterable
    {
        $dict = ['a' => 'A', 'n' => null, 5 => 'five'];

        yield 'present' => [$dict, 'a', 'A'];
        yield 'present null' => [$dict, 'n', null];
        yield 'absent' => [$dict, 'zz', null];
        yield 'int key' => [$dict, 5, 'five'];
        yield 'numeric string key' => [$dict, '5', 'five'];
        yield 'list index' => [['x', 'y'], 1, 'y'];
        yield 'empty array' => [[], 'a', null];
        yield 'null subject' => [null, 'a', null];
        yield 'string subject' => ['abc', 0, null];
        yield 'array access subject' => [new ArrayObject(['a' => 'A']), 'a', null];
    }

    public static function provideSetSubjects(): iterable
    {
        yield 'present' => [['a' => 'A'], 'a', ['a' => 'new']];
        yield 'absent' => [[], 'a', ['a' => 'new']];
        yield 'int key' => [['x'], 1, ['x', 'new']];
        yield 'null subject' => [null, 'a', null];
    }
}

<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Lens;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;
use stdClass;
use VeeWee\Reflecta\TestFixtures\Optional\DynamicSubject;
use VeeWee\Reflecta\TestFixtures\Optional\HookedSubject;
use VeeWee\Reflecta\TestFixtures\Optional\MagicSubject;
use VeeWee\Reflecta\TestFixtures\Optional\TypedSubject;
use function VeeWee\Reflecta\Lens\optional;
use function VeeWee\Reflecta\Lens\optional_property;
use function VeeWee\Reflecta\Lens\property;

final class OptionalPropertyTest extends TestCase
{
    #[DataProvider('provideSubjects')]
    public function test_it_gets_like_the_optional_property_composition(mixed $subject, string $name, mixed $expected): void
    {
        $actual = optional_property($name)->get($subject);

        static::assertSame($expected, $actual);
        static::assertSame(@optional(property($name))->get($subject), $actual);
    }

    public function test_it_reuses_one_lens_over_all_subjects(): void
    {
        $lenses = [];
        foreach (self::provideSubjects() as $label => [$subject, $name, $expected]) {
            $lens = $lenses[$name] ??= optional_property($name);

            static::assertSame($expected, $lens->get($subject), $label);
        }
    }

    public function test_it_does_not_share_dynamic_properties_between_instances(): void
    {
        $lens = optional_property('a');

        static::assertSame('A', $lens->get((object) ['a' => 'A']));
        static::assertNull($lens->get((object) ['b' => 'B']));
        static::assertSame('A2', $lens->get((object) ['a' => 'A2']));

        $dynamic = new DynamicSubject();
        $dynamic->{'a'} = 'dynamic';
        static::assertSame('dynamic', $lens->get($dynamic));
        static::assertNull($lens->get(new DynamicSubject()));
    }

    #[DataProvider('provideSetSubjects')]
    public function test_it_sets_like_the_optional_property_composition(mixed $subject, string $name, mixed $expected): void
    {
        $actual = optional_property($name)->set($subject, 'new');

        static::assertEquals($expected, $actual);
        static::assertEquals(optional(property($name))->set($subject, 'new'), $actual);
        if ($actual !== null) {
            static::assertNotSame($subject, $actual);
        }
    }

    public static function provideSubjects(): iterable
    {
        $typed = new TypedSubject();
        $std = (object) ['a' => 'A', 'n' => null];
        $cast = (object) [5 => 'five', 'k' => 'v'];
        $dynamic = new DynamicSubject();
        $dynamic->{'extra'} = 'dynamic';
        $hooked = new HookedSubject();
        $magic = new MagicSubject();

        yield 'typed public' => [$typed, 'public', 'public'];
        yield 'typed protected' => [$typed, 'protected', 'protected'];
        yield 'typed private' => [$typed, 'private', 'private'];
        yield 'private in parent' => [$typed, 'secret', 'parent-private'];
        yield 'readonly' => [$typed, 'readonly', 'readonly'];
        yield 'nullable null' => [$typed, 'nullable', null];
        yield 'uninitialized' => [$typed, 'uninitialized', null];
        yield 'unset' => [$typed, 'unset', null];
        yield 'untyped unset' => [$typed, 'untypedUnset', null];
        yield 'static' => [$typed, 'static', 'static'];
        yield 'undeclared' => [$typed, 'nope', null];
        yield 'stdClass present' => [$std, 'a', 'A'];
        yield 'stdClass null' => [$std, 'n', null];
        yield 'stdClass absent' => [$std, 'zz', null];
        yield 'array cast int key' => [$cast, '5', 'five'];
        yield 'array cast string key' => [$cast, 'k', 'v'];
        yield 'array cast absent' => [$cast, 'q', null];
        yield 'dynamic class declared' => [$dynamic, 'declared', 'declared'];
        yield 'dynamic class dynamic' => [$dynamic, 'extra', 'dynamic'];
        yield 'dynamic class absent' => [$dynamic, 'nope', null];
        yield 'get hook' => [$hooked, 'hooked', 'hooked:raw'];
        yield 'virtual hook' => [$hooked, 'virtual', 'virtual'];
        yield 'throwing hook' => [$hooked, 'throwing', null];
        yield 'magic undeclared' => [$magic, 'x', null];
        yield 'magic unset' => [$magic, 'unset', 'magic:unset'];
        yield 'magic uninitialized' => [$magic, 'uninitialized', null];
        yield 'other stdClass absent' => [(object) ['b' => 1], 'a', null];
        yield 'lazy ghost with failing initializer' => [
            (new ReflectionClass(TypedSubject::class))->newLazyGhost(static fn () => throw new RuntimeException('Init failed')),
            'public',
            null,
        ];
        yield 'null subject' => [null, 'a', null];
        yield 'array subject' => [['a' => 'A'], 'a', null];
    }

    public static function provideSetSubjects(): iterable
    {
        $typed = new TypedSubject();
        $std = new stdClass();
        $expectedStd = new stdClass();
        $expectedStd->a = 'new';
        $expectedTyped = new TypedSubject();
        $expectedTyped->public = 'new';

        yield 'typed public' => [$typed, 'public', $expectedTyped];
        yield 'readonly' => [$typed, 'readonly', null];
        yield 'undeclared on stdClass' => [$std, 'a', $expectedStd];
        yield 'null subject' => [null, 'a', null];
    }
}

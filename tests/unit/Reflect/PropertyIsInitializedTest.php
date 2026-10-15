<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;
use VeeWee\Reflecta\TestFixtures\Optional\DynamicSecretSubject;
use VeeWee\Reflecta\TestFixtures\Optional\DynamicSubject;
use VeeWee\Reflecta\TestFixtures\Optional\HookedSubject;
use VeeWee\Reflecta\TestFixtures\Optional\MagicSubject;
use VeeWee\Reflecta\TestFixtures\Optional\SparseSecretSubject;
use VeeWee\Reflecta\TestFixtures\Optional\TypedSubject;
use function VeeWee\Reflecta\Lens\optional_property;
use function VeeWee\Reflecta\Reflect\property_is_initialized;

final class PropertyIsInitializedTest extends TestCase
{
    #[DataProvider('provideSubjects')]
    public function test_it_checks_if_a_property_is_initialized(object $subject, string $name, bool $expected): void
    {
        static::assertSame($expected, property_is_initialized($subject, $name));
    }

    #[DataProvider('provideSubjects')]
    public function test_it_resolves_properties_like_optional_property(object $subject, string $name, bool $expected): void
    {
        $value = optional_property($name)->get($subject);
        $actual = property_is_initialized($subject, $name);

        if ($value !== null) {
            static::assertTrue($actual);
            return;
        }

        static::assertSame($expected, $actual);
    }

    public function test_it_does_not_consult_magic_get(): void
    {
        $subject = new MagicSubject();

        static::assertFalse(property_is_initialized($subject, 'unset'));
        static::assertFalse(property_is_initialized($subject, 'uninitialized'));
        static::assertFalse(property_is_initialized($subject, 'x'));
    }

    public function test_it_finds_a_dynamic_property_that_shadows_an_uninitialized_private_parent_property(): void
    {
        $subject = new SparseSecretSubject();
        static::assertFalse(property_is_initialized($subject, 'uninitializedSecret'));

        $subject->{'uninitializedSecret'} = null;
        static::assertTrue(property_is_initialized($subject, 'uninitializedSecret'));
    }

    public function test_it_initializes_a_lazy_ghost_to_check_it(): void
    {
        $ghost = (new ReflectionClass(TypedSubject::class))->newLazyGhost(
            static function (TypedSubject $subject): void {
                $subject->__construct();
            }
        );

        static::assertTrue(property_is_initialized($ghost, 'public'));
        static::assertFalse((new ReflectionClass(TypedSubject::class))->isUninitializedLazyObject($ghost));
        static::assertFalse(property_is_initialized($ghost, 'uninitialized'));
        static::assertFalse(property_is_initialized($ghost, 'unset'));
    }

    public function test_it_initializes_a_lazy_proxy_to_check_it(): void
    {
        $proxy = (new ReflectionClass(TypedSubject::class))->newLazyProxy(
            static fn (): TypedSubject => new TypedSubject()
        );

        static::assertTrue(property_is_initialized($proxy, 'nullable'));
        static::assertFalse(property_is_initialized($proxy, 'uninitialized'));
        static::assertFalse(property_is_initialized($proxy, 'nope'));
    }

    public static function provideSubjects(): iterable
    {
        $typed = new TypedSubject();
        $std = (object) ['a' => 'A', 'n' => null];
        $unsetStd = (object) ['gone' => 'gone'];
        unset($unsetStd->gone);
        $dynamic = new DynamicSubject();
        $dynamic->{'extra'} = 'dynamic';
        $dynamic->{'extraNull'} = null;
        $shadowed = new DynamicSecretSubject();
        $shadowed->{'secret'} = null;
        $sparse = new SparseSecretSubject();
        $shadowedSparse = new SparseSecretSubject();
        $shadowedSparse->{'uninitializedSecret'} = null;
        $hooked = new HookedSubject();
        $failingGhost = static fn (): object => (new ReflectionClass(TypedSubject::class))->newLazyGhost(
            static fn () => throw new RuntimeException('Init failed')
        );
        $failingProxy = static fn (): object => (new ReflectionClass(TypedSubject::class))->newLazyProxy(
            static fn () => throw new RuntimeException('Init failed')
        );

        yield 'typed initialized' => [$typed, 'public', true];
        yield 'typed protected' => [$typed, 'protected', true];
        yield 'typed private' => [$typed, 'private', true];
        yield 'typed initialized null' => [$typed, 'nullable', true];
        yield 'typed uninitialized' => [$typed, 'uninitialized', false];
        yield 'typed unset' => [$typed, 'unset', false];
        yield 'untyped unset' => [$typed, 'untypedUnset', false];
        yield 'readonly initialized' => [$typed, 'readonly', true];
        yield 'readonly uninitialized' => [$sparse, 'readonlyUninitialized', false];
        yield 'static' => [$typed, 'static', true];
        yield 'undeclared' => [$typed, 'nope', false];
        yield 'private in parent' => [$typed, 'secret', true];
        yield 'protected in parent' => [$typed, 'inherited', true];
        yield 'private in parent initialized null' => [$sparse, 'nullSecret', true];
        yield 'private in parent uninitialized' => [$sparse, 'uninitializedSecret', false];
        yield 'dynamic null shadowing private in parent' => [$shadowed, 'secret', true];
        yield 'dynamic null shadowing uninitialized private in parent' => [$shadowedSparse, 'uninitializedSecret', true];
        yield 'stdClass present' => [$std, 'a', true];
        yield 'stdClass present null' => [$std, 'n', true];
        yield 'stdClass missing' => [$std, 'zz', false];
        yield 'stdClass unset' => [$unsetStd, 'gone', false];
        yield 'dynamic class declared' => [$dynamic, 'declared', true];
        yield 'dynamic class dynamic' => [$dynamic, 'extra', true];
        yield 'dynamic class dynamic null' => [$dynamic, 'extraNull', true];
        yield 'dynamic class missing' => [$dynamic, 'nope', false];
        yield 'get hook' => [$hooked, 'hooked', true];
        yield 'virtual hook' => [$hooked, 'virtual', true];
        yield 'throwing hook' => [$hooked, 'throwing', true];
        yield 'lazy ghost with failing initializer' => [$failingGhost(), 'public', true];
        yield 'lazy ghost private in parent with failing initializer' => [$failingGhost(), 'secret', true];
        yield 'lazy ghost missing with failing initializer' => [$failingGhost(), 'nope', true];
        yield 'lazy proxy with failing initializer' => [$failingProxy(), 'uninitialized', true];
    }
}

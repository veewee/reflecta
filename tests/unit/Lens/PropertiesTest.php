<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Lens;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Reflect\Type\ReflectedProperty;
use function VeeWee\Reflecta\Lens\properties;

final class PropertiesTest extends TestCase
{

    public function test_it_can_work_with_properties(): void
    {
        $lens = properties();
        $expected = ['hello' => 'world'];
        $data = (object)$expected;

        static::assertSame($expected, $lens->get($data));
        static::assertSame($expected, $lens->tryGet($data)->getResult());
        static::assertTrue($lens->tryGet(null)->isFailed());

        static::assertEquals((object)['hello' => 'earth'], $lens->set($data, ['hello' => 'earth']));
        static::assertNotSame($data, $lens->set($data, ['hello' => 'earth']));
        static::assertEquals((object)['hello' => 'earth'], $lens->trySet($data, ['hello' => 'earth'])->getResult());
        static::assertTrue($lens->trySet(null, 'earth')->isFailed());
    }

    public function test_it_can_apply_a_predicate(): void
    {
        $lens = properties(static fn (ReflectedProperty $property) => $property->name() === 'hello');
        $expected = ['hello' => 'world'];
        $moreInfo = [
            ...$expected,
            'goodbye' => 'earth',
        ];
        $data = (object)$moreInfo;

        static::assertSame($expected, $lens->get($data));
        static::assertSame($expected, $lens->tryGet($data)->getResult());
        static::assertTrue($lens->tryGet(null)->isFailed());

        static::assertEquals((object)[...$moreInfo, 'hello' => 'earth'], $lens->set($data, ['hello' => 'earth', 'goodbye' => 'Toon']));
        static::assertNotSame($data, $lens->set($data, ['hello' => 'earth']));
        static::assertEquals((object)[...$moreInfo, 'hello' => 'earth'], $lens->trySet($data, ['hello' => 'earth', 'goodbye' => 'Toon'])->getResult());
        static::assertTrue($lens->trySet(null, 'earth')->isFailed());
    }
}

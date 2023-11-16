<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Lens;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use VeeWee\Reflecta\Lens\Lens;
use function VeeWee\Reflecta\Lens\index;

final class LensTest extends TestCase
{

    public function test_it_can_get_data(): void
    {
        $lens = new Lens(
            static fn (array $data) => $data['hello'],
            static fn (array $data, string $value) => [...$data, 'hello' => $value],
        );

        $data = ['hello' => 'world'];
        static::assertSame('world', $lens->get($data));
        static::assertSame('world', $lens->tryGet($data)->getResult());
    }


    public function test_it_can_set_data(): void
    {
        $lens = new Lens(
            static fn (array $data) => $data['hello'],
            static fn (array $data, string $value) => [...$data, 'hello' => $value],
        );

        $data = ['hello' => 'world'];
        static::assertSame(['hello' => 'earth'], $lens->set($data, 'earth'));
        static::assertSame(['hello' => 'earth'], $lens->trySet($data, 'earth')->getResult());
    }


    public function test_it_can_update_data(): void
    {
        $lens = new Lens(
            static fn (array $data) => $data['hello'],
            static fn (array $data, string $value) => [...$data, 'hello' => $value],
        );

        $data = ['hello' => 'w'];
        static::assertSame(
            ['hello' => 'world'],
            $lens->update($data, static fn (string $message) => $message.'orld')
        );
        static::assertSame(
            ['hello' => 'world'],
            $lens->tryUpdate($data, static fn (string $message) => $message.'orld')->getResult()
        );
    }


    public function test_it_can_have_identity(): void
    {
        $lens = Lens::identity();

        static::assertSame('hello', $lens->get('hello'));
        static::assertSame('hello', $lens->set('ignored', 'hello'));
    }


    public function test_it_can_be_optional(): void
    {
        $lens = (new Lens(
            static function (array $data): mixed {
                if (!array_key_exists('hello', $data)) {
                    throw new RuntimeException('nope');
                }
                return $data['hello'];
            },
            static function (array $data, mixed $value): mixed {
                if (!array_key_exists('hello', $data)) {
                    throw new RuntimeException('nope');
                }

                return [...$data, 'hello' => $value];
            },
        ))->optional();

        $validData = ['hello' => 'world'];
        $invalidData = [];

        static::assertSame('world', $lens->get($validData));
        static::assertSame(null, $lens->get($invalidData));

        static::assertSame(['hello' => 'earth'], $lens->set($validData, 'earth'));
        static::assertSame(null, $lens->set($invalidData, 'earth'));
    }


    public function test_it_can_compose_lenses(): void
    {
        $greetLens = index('greet');
        $messageLens = index('message');
        $composed = $greetLens->compose($messageLens);

        $data = ['greet' => ['message' => 'hello']];

        static::assertSame('hello', $composed->get($data));
        static::assertSame(['greet' => ['message' => 'goodbye']], $composed->set($data, 'goodbye'));
    }
}

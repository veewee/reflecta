<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Lens;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Lens\Lens;
use function VeeWee\Reflecta\Lens\index;

class LensTest extends TestCase
{
    /** @test */
    public function it_can_get_data(): void
    {
        $lens = new Lens(
            fn (array $data) => $data['hello'],
            fn (array $data, string $value) => [...$data, 'hello' => $value],
        );

        $data = ['hello' => 'world'];
        self::assertSame('world', $lens->get($data));
        self::assertSame('world', $lens->tryGet($data)->getResult());
    }

    /** @test */
    public function it_can_set_data(): void
    {
        $lens = new Lens(
            fn (array $data) => $data['hello'],
            fn (array $data, string $value) => [...$data, 'hello' => $value],
        );

        $data = ['hello' => 'world'];
        self::assertSame(['hello' => 'earth'], $lens->set($data, 'earth'));
        self::assertSame(['hello' => 'earth'], $lens->trySet($data, 'earth')->getResult());
    }

    /** @test */
    public function it_can_update_data(): void
    {
        $lens = new Lens(
            fn (array $data) => $data['hello'],
            fn (array $data, string $value) => [...$data, 'hello' => $value],
        );

        $data = ['hello' => 'w'];
        self::assertSame(
            ['hello' => 'world'],
            $lens->update($data, fn (string $message) => $message.'orld')
        );
        self::assertSame(
            ['hello' => 'world'],
            $lens->tryUpdate($data, fn (string $message) => $message.'orld')->getResult()
        );
    }

    /** @test */
    public function it_can_have_identity(): void
    {
        $lens = Lens::identity();

        self::assertSame('hello', $lens->get('hello'));
        self::assertSame('hello', $lens->set('hello', 'ignored'));
    }

    /** @test */
    public function it_can_be_optional(): void
    {
        $lens = (new Lens(
            static function (array $data): mixed {
                if (!array_key_exists('hello', $data)) {
                    throw new \RuntimeException('nope');
                }
                return $data['hello'];
            },
            static function (array $data, mixed $value): mixed {
                if (!array_key_exists('hello', $data)) {
                    throw new \RuntimeException('nope');
                }

                return [...$data, 'hello' => $value];
            },
        ))->optional();

        $validData = ['hello' => 'world'];
        $invalidData = [];

        self::assertSame('world', $lens->get($validData));
        self::assertSame(null, $lens->get($invalidData));

        self::assertSame(['hello' => 'earth'], $lens->set($validData, 'earth'));
        self::assertSame(null, $lens->set($invalidData, 'earth'));
    }

    /** @test */
    public function it_can_compose_lenses(): void
    {
        $greetLens = index('greet');
        $messageLens = index('message');
        $composed = $greetLens->compose($messageLens);

        $data = ['greet' => ['message' => 'hello']];

        self::assertSame('hello', $composed->get($data));
        self::assertSame(['greet' => ['message' => 'goodbye']], $composed->set($data, 'goodbye'));
    }
}

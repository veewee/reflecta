<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Lens;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Lens\Lens;
use function VeeWee\Reflecta\Lens\optional;

class OptionalTest extends TestCase
{
    /** @test */
    public function it_can_be_optional(): void
    {
        $lens = optional(new Lens(
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
        ));

        $validData = ['hello' => 'world'];
        $invalidData = [];

        self::assertSame('world', $lens->get($validData));
        self::assertSame(null, $lens->get($invalidData));

        self::assertSame(['hello' => 'earth'], $lens->set($validData, 'earth'));
        self::assertSame(null, $lens->set($invalidData, 'earth'));
    }

}

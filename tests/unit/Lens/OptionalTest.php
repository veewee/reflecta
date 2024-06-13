<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Lens;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use VeeWee\Reflecta\Lens\Lens;
use function array_key_exists;
use function VeeWee\Reflecta\Lens\optional;

final class OptionalTest extends TestCase
{
    
    public function test_it_can_be_optional(): void
    {
        $lens = optional(new Lens(
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
        ));

        $validData = ['hello' => 'world'];
        $invalidData = [];

        static::assertSame('world', $lens->get($validData));
        static::assertSame(null, $lens->get($invalidData));

        static::assertSame(['hello' => 'earth'], $lens->set($validData, 'earth'));
        static::assertSame(null, $lens->set($invalidData, 'earth'));
    }

}

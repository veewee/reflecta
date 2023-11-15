<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Iso;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Iso\Iso;
use function VeeWee\Reflecta\Iso\compose;

final class ComposeTest extends TestCase
{
    
    public function test_it_can_be_composed(): void
    {
        $base64 = new Iso(
            base64_encode(...),
            base64_decode(...),
        );

        $commaSeparated = new Iso(
            static fn (array $keywords): string => join(',', $keywords),
            static fn (string $keywords): array => explode(',', $keywords)
        );

        $commaSeparatedBase64 = compose($commaSeparated, $base64);

        $data = ['hello' ,'world'];
        $joined = $commaSeparatedBase64->to($data);
        $exploded = $commaSeparatedBase64->from($joined);

        static::assertSame(base64_encode('hello,world'), $joined);
        static::assertSame($data, $exploded);
    }
}

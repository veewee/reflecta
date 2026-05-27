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

    public function test_it_composes_a_single_iso_as_passthrough(): void
    {
        $base64 = new Iso(
            base64_encode(...),
            base64_decode(...),
        );

        $composed = compose($base64);

        static::assertSame(base64_encode('hello'), $composed->to('hello'));
        static::assertSame('hello', $composed->from(base64_encode('hello')));
    }

    public function test_it_composes_an_empty_list_as_identity(): void
    {
        /** @var list<\VeeWee\Reflecta\Iso\IsoInterface<mixed, mixed, mixed, mixed>> $isos */
        $isos = [];
        $composed = compose(...$isos);

        static::assertSame('hello', $composed->to('hello'));
        static::assertSame('hello', $composed->from('hello'));
    }
}

<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Iso;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use VeeWee\Reflecta\Iso\Iso;

final class IsoTest extends TestCase
{
    
    public function test_it_is_isomorphic(): void
    {
        $commaSeparated = new Iso(
            static fn (array $keywords): string => join(',', $keywords),
            static fn (string $keywords): array => explode(',', $keywords)
        );

        $data = ['hello' ,'world'];
        $joined = $commaSeparated->to($data);
        $exploded = $commaSeparated->from($joined);

        static::assertSame('hello,world', $joined);
        static::assertSame($data, $exploded);
    }

    
    public function test_it_can_succeed_trying_to_be_isomorphic(): void
    {
        $commaSeparated = new Iso(
            static fn (array $keywords): string => join(',', $keywords),
            static fn (string $keywords): array => explode(',', $keywords)
        );

        $data = ['hello' ,'world'];
        $joined = $commaSeparated->tryTo($data);
        $exploded = $commaSeparated->tryFrom('hello,world');

        static::assertSame('hello,world', $joined->getResult());
        static::assertSame($data, $exploded->getResult());
    }

    
    public function test_it_can_fail_trying_to_be_isomorphic(): void
    {
        $exception = new RuntimeException('fail');
        $commaSeparated = new Iso(
            static fn (array $keywords): string => throw $exception,
            static fn (string $keywords): array => throw $exception
        );

        $data = ['hello' ,'world'];
        $joined = $commaSeparated->tryTo($data);
        $exploded = $commaSeparated->tryFrom('hello,world');

        static::assertSame($exception, $joined->getThrowable());
        static::assertSame($exception, $exploded->getThrowable());
    }

    
    public function test_it_has_identity(): void
    {
        $identity = Iso::identity();
        $initial = "hello";
        $to = $identity->to($initial);
        $from = $identity->from($initial);

        static::assertSame($initial, $to);
        static::assertSame($initial, $from);
    }

    
    public function test_it_can_be_inversed(): void
    {
        $commaSeparated = (new Iso(
            static fn (array $keywords): string => join(',', $keywords),
            static fn (string $keywords): array => explode(',', $keywords)
        ))->inverse();

        $data = ['hello' ,'world'];
        $joined = $commaSeparated->from($data);
        $exploded = $commaSeparated->to($joined);

        static::assertSame('hello,world', $joined);
        static::assertSame($data, $exploded);
    }

    
    public function test_it_can_be_transformed_in_a_lens(): void
    {
        $commaSeparated = new Iso(
            static fn (array $keywords): string => join(',', $keywords),
            static fn (string $keywords): array => explode(',', $keywords)
        );
        $lens = $commaSeparated->asLens();

        $data = ['hello' ,'world'];
        $joined = $lens->get($data);
        $exploded = $lens->set(null, $joined);

        static::assertSame('hello,world', $joined);
        static::assertSame($data, $exploded);
    }

    
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

        $commaSeparatedBase64 = $commaSeparated->compose($base64);

        $data = ['hello' ,'world'];
        $joined = $commaSeparatedBase64->to($data);
        $exploded = $commaSeparatedBase64->from($joined);

        static::assertSame(base64_encode('hello,world'), $joined);
        static::assertSame($data, $exploded);
    }
}

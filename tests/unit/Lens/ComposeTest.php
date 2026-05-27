<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Lens;

use PHPUnit\Framework\TestCase;
use function VeeWee\Reflecta\Lens\compose;
use function VeeWee\Reflecta\Lens\index;

final class ComposeTest extends TestCase
{
    public function test_it_can_compose_lenses(): void
    {
        $greetLens = index('greet');
        $messageLens = index('message');
        $composed = compose($greetLens, $messageLens);

        $data = ['greet' => ['message' => 'hello']];

        static::assertSame('hello', $composed->get($data));
        static::assertSame(['greet' => ['message' => 'goodbye']], $composed->set($data, 'goodbye'));
    }

    public function test_it_composes_a_single_lens_as_passthrough(): void
    {
        $composed = compose(index('greet'));
        $data = ['greet' => 'hello'];

        static::assertSame('hello', $composed->get($data));
        static::assertSame(['greet' => 'goodbye'], $composed->set($data, 'goodbye'));
    }

    public function test_it_composes_an_empty_list_as_identity(): void
    {
        /** @var list<\VeeWee\Reflecta\Lens\LensInterface<mixed, mixed, mixed, mixed>> $lenses */
        $lenses = [];
        $composed = compose(...$lenses);
        $data = ['greet' => 'hello'];

        static::assertSame($data, $composed->get($data));
        static::assertSame(['other' => 'value'], $composed->set($data, ['other' => 'value']));
    }
}

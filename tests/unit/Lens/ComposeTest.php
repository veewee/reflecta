<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Lens;

use PHPUnit\Framework\TestCase;
use function VeeWee\Reflecta\Lens\compose;
use function VeeWee\Reflecta\Lens\index;

class ComposeTest extends TestCase
{
    /** @test */
    public function it_can_compose_lenses(): void
    {
        $greetLens = index('greet');
        $messageLens = index('message');
        $composed = compose($greetLens, $messageLens);

        $data = ['greet' => ['message' => 'hello']];

        self::assertSame('hello', $composed->get($data));
        self::assertSame(['greet' => ['message' => 'goodbye']], $composed->set($data, 'goodbye'));
    }
}

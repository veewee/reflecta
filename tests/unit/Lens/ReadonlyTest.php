<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Lens;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Exception\ReadonlyException;
use function VeeWee\Reflecta\Lens\property;
use function VeeWee\Reflecta\Lens\read_only;

final class ReadonlyTest extends TestCase
{
    
    public function test_it_can_be_readonly(): void
    {
        $lens = read_only(property('foo'));
        $data = (object) ['foo' => 'bar'];

        static::assertSame('bar', $lens->get($data));

        $this->expectExceptionObject(ReadonlyException::couldNotWrite());
        $lens->set('hello', $data);
    }
}

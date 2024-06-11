<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Type;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ThisIsAnUnknownAttribute;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedAttribute;
use VeeWee\Reflecta\TestFixtures\CustomAttribute;
use VeeWee\Reflecta\TestFixtures\RepeatedAttribute;

final class ReflectedAttributeTest extends TestCase
{
    public function test_it_can_get_full_name(): void
    {
        $x = new #[CustomAttribute] class {};
        $attribute = (new ReflectionClass($x))->getAttributes()[0];
        $reflected = new ReflectedAttribute($attribute);

        static::assertSame(CustomAttribute::class, $reflected->fullName());
    }

    public function test_it_knows_if_it_is_not_repeated(): void
    {
        $x = new #[CustomAttribute] class {};
        $attribute = (new ReflectionClass($x))->getAttributes()[0];
        $reflected = new ReflectedAttribute($attribute);

        static::assertFalse($reflected->isRepeated());
    }

    public function test_it_knows_if_it_is_repeated(): void
    {
        $x = new #[RepeatedAttribute] #[RepeatedAttribute] class {};
        $attribute = (new ReflectionClass($x))->getAttributes()[0];
        $reflected = new ReflectedAttribute($attribute);

        static::assertTrue($reflected->isRepeated());
    }

    public function test_it_can_be_instantiated(): void
    {
        $x = new #[CustomAttribute] class {};
        $attribute = (new ReflectionClass($x))->getAttributes()[0];
        $reflected = new ReflectedAttribute($attribute);

        static::assertEquals(new CustomAttribute(), $reflected->instantiate());
    }

    public function test_it_can_not_instantiate(): void
    {
        $x = new #[ThisIsAnUnknownAttribute] class {};
        $attribute = (new ReflectionClass($x))->getAttributes()[0];
        $reflected = new ReflectedAttribute($attribute);

        $this->expectException(UnreflectableException::class);
        $this->expectExceptionMessage('Unable to instantiate class ThisIsAnUnknownAttribute.');

        $reflected->instantiate();
    }

}

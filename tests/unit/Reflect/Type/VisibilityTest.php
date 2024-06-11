<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\UnitTests\Reflect\Type;

use PHPUnit\Framework\TestCase;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;
use VeeWee\Reflecta\Reflect\Type\Visibility;
use VeeWee\Reflecta\TestFixtures\X;

final class VisibilityTest extends TestCase
{
    public function test_it_knows_visibility_from_property(): void
    {
        $prop = ReflectedClass::fromFullyQualifiedClassName(X::class)->property('z');
        $visibility = $prop->visibility();

        static::assertSame(Visibility::Public, $visibility);
    }
}

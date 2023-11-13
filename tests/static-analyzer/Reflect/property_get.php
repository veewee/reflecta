<?php

namespace VeeWee\ReflectaTests\StaticAnalyzer;

use function VeeWee\Reflecta\Reflect\property_get;

class X {
    public ?int $z = 0;
}
$z = 'z';
$x = new \stdClass();
$x->z = 123;

/**
 * @template T of object
 * @param string $path
 * @return \Closure(T): mixed
 */
function curried(string $path): \Closure {
    return static fn (object $object): mixed => property_get($object, $path);
}


//$y = property_get($x, $z);
$y = \VeeWee\Reflecta\curried($z)($x);

/** @psalm-trace $y */

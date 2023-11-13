<?php

use function VeeWee\Reflecta\Reflect\property;

require_once __DIR__.'/vendor/autoload.php';


class Foo
{
}

class Bar
{
    /** @var Foo */
    public $foo;

    public function __construct(Foo $foo)
    {
        $this->foo = $foo;
    }
}

class Baz
{
    /** @var Bar */
    public $bar;

    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }
}

$fooBar = property('foo');
$barBaz = property('bar');

$fooBaz = $barBaz->compose($fooBar);

$foo = new Foo();
$bar = new Bar($foo);
$baz = new Baz($bar);

var_dump($fooBaz->get($baz));


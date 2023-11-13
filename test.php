<?php

use VeeWee\Reflecta\Optic\Iso;
use function VeeWee\Reflecta\Reflect\optional;
use function VeeWee\Reflecta\Reflect\property;

require_once __DIR__.'/vendor/autoload.php';


$iso = new Iso(
    fn(array $s) => ['from' => $s['from']],
    fn(array $s) => ['to' => $s['to']]
);

var_dump(
    $iso->from(['to' => 1]),
    $iso->to(['from' => 1])
);

$lens = $iso->asLens();
var_dump(
    $lens->get(['from' => 1]),
    $lens->set(null, ['to' => 2])
);
//var_dump();
exit;






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
    /** @var Bar|null */
    public $bar;

    public function __construct(?Bar $bar)
    {
        $this->bar = $bar;
    }
}

$barBaz = optional(property('bar'));
var_dump($barBaz->get(new Baz(null)));
var_dump($barBaz->get($set = new Baz(new Bar(new Foo()))));


var_dump($barBaz->set(
    $set,
    Psl\Option\none()
));
exit;




var_dump($set, $barBaz->get($set));
exit;



$fooBar = property('foo');
$barBaz = property('bar');

$fooBaz = $barBaz->compose($fooBar);

$foo = new Foo();
$bar = new Bar($foo);
$baz = new Baz(null);

var_dump($fooBaz->get($baz));


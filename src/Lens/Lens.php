<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

/**
 * @template A
 * @template B
 * @template S
 * @template T
 *
 * @psalm-immutable
 */
final class Lens
{
    /**
     * @var callable(S): A
     */
    private $get;

    /**
     * @var callable(S, B): T
     */
    private $set;

    /**
     * @param callable(S): A $get
     * @param callable(S, B): T $set
     */
    public function __construct(callable $get, callable $set)
    {
        $this->get = $get;
        $this->set = $set;
    }

    /**
     * @param S $s
     * @return A
     */
    public function get($s)
    {
        return ($this->get)($s);
    }

    /**
     * @param S $s
     * @param B $b
     * @return T
     */
    public function set($s, $b)
    {
        return ($this->set)($s, $b);
    }

    /**
     * @param callable(A): B $f
     * @param S $s
     * @return T
     */
    public function update(callable $f, $s)
    {
        return $this->set($s, $f(($this->get)($s)));
    }

    /**
     * @template C
     * @template D
     * @param Lens<A, B, C, D> $that
     * @return Lens<S, T, C, D>
     */
    public function compose(Lens $that): Lens
    {
        /** @psalm-suppress InvalidArgument */
        return new self(
            (/**
             * @param S $s
             * @return C
             */
            fn($s) => $that->get(($this->get)($s))),
            (/**
             * @param S $s
             * @param D $d
             * @return T
             */
            fn($s, $d) => $this->set($s, $that->set($this->get($s), $d)))
        );
    }
}

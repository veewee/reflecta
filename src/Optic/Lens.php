<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Optic;

/**
 * @template S
 * @template A
 *
 * @psalm-immutable
 * @psalm-suppress ImpureFunctionCall
 */
final class Lens
{
    /**
     * @var callable(S): A
     */
    private $get;

    /**
     * @var callable(S, A): S
     */
    private $set;

    /**
     * @param callable(S): A $get
     * @param callable(S, A): S $set
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
     * @param A $b
     * @return S
     */
    public function set($s, $b)
    {
        return ($this->set)($s, $b);
    }

    /**
     * @param callable(A): A $f
     * @param S $s
     * @return S
     */
    public function update(callable $f, $s)
    {
        return $this->set($s, $f(($this->get)($s)));
    }

    /**
     * @template S2
     * @template A2
     * @param Lens<S2, A2> $that
     * @return Lens<S, A2>
     */
    public function compose(Lens $that): Lens
    {
        /** @psalm-suppress InvalidArgument */
        return new self(
            /**
             * @param S $s
             * @return A2
             */
            fn($s) => $that->get(($this->get)($s)),
            /**
             * @param S $s
             * @param A2 $a2
             * @return S
             */
            fn($s, $a2) => $this->set($s, $that->set($this->get($s), $a2))
        );
    }
}

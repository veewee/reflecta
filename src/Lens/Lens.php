<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

use Psl\Result\ResultInterface;
use function Psl\Result\wrap;

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
     * @pure
     * @template I
     * @return Lens<I, I>
     */
    public static function identity(): self
    {
        return new self(
            /**
             * @param I $s
             * @returns I
             */
            static fn ($s) => $s,
            /**
             * @param I $s
             * @param mixed $_
             * @returns I
             */
            static fn ($s, $_) => $s
        );
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
     * @return ResultInterface<A>
     */
    public function tryGet($s): ResultInterface
    {
        return wrap(fn() => ($this->get)($s));
    }

    /**
     * @param S $s
     * @param A $a
     * @return S
     */
    public function set($s, $a)
    {
        return ($this->set)($s, $a);
    }

    /**
     * @param S $s
     * @param A $a
     * @return ResultInterface<S>
     */
    public function trySet($s, $a): ResultInterface
    {
        return wrap(fn() => ($this->set)($s, $a));
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
     * @param callable(A): A $f
     * @param S $s
     * @return ResultInterface<S>
     */
    public function tryUpdate(callable $f, $s): ResultInterface
    {
        return wrap(fn() => $this->set($s, $f(($this->get)($s))));
    }

    /**
     * @return Lens<S, A|null>
     */
    public function optional(): Lens
    {
        return optional($this);
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

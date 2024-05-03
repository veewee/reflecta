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
 * @implements LensInterface<S, A>
 */
final class Lens implements LensInterface
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
             * @param I $_
             * @param I $a
             * @returns I
             */
            static fn ($_, $a) => $a
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
        return wrap(fn () => ($this->get)($s));
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
        return wrap(fn () => ($this->set)($s, $a));
    }

    /**
     * @param S $s
     * @param callable(A): A $f
     * @return S
     */
    public function update($s, callable $f)
    {
        return $this->set($s, $f(($this->get)($s)));
    }

    /**
     * @param S $s
     * @param callable(A): A $f
     * @return ResultInterface<S>
     */
    public function tryUpdate($s, callable $f): ResultInterface
    {
        return wrap(fn () => $this->set($s, $f(($this->get)($s))));
    }

    /**
     * @return LensInterface<S, A|null>
     */
    public function optional(): LensInterface
    {
        return optional($this);
    }

    /**
     * @template S2
     * @template A2
     * @param LensInterface<S2, A2> $that
     * @return LensInterface<S, A2>
     */
    public function compose(LensInterface $that): LensInterface
    {
        /** @psalm-suppress InvalidArgument */
        return new self(
            /**
             * @param S $s
             * @return A2
             */
            fn ($s) => $that->get(($this->get)($s)),
            /**
             * @param S $s
             * @param A2 $a2
             * @return S
             */
            fn ($s, $a2) => $this->set($s, $that->set($this->get($s), $a2))
        );
    }
}

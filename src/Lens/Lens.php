<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

use Psl\Result\ResultInterface;
use VeeWee\Reflecta\Exception\ReadonlyException;
use function Psl\Result\wrap;

/**
 * @template S
 * @template T
 * @template A
 * @template B
 *
 * @psalm-immutable
 * @psalm-suppress ImpureFunctionCall
 * @implements LensInterface<S, T, A, B>
 */
final class Lens implements LensInterface
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
     * @pure
     * @template S2
     * @template A2
     * @param callable(S2): A2 $get
     * @return Lens<S2, S2, A2, A2>
     */
    public static function readonly(callable $get): self
    {
        /**
         * The setter only ever throws, so its return type is `never` and `T` cannot be
         * inferred as `S2` from the closure alone.
         *
         * @var Lens<S2, S2, A2, A2>
         */
        return new self($get, static fn ($s, $a) => throw ReadonlyException::couldNotWrite());
    }

    /**
     * @pure
     * @template I
     * @return Lens<I, I, I, I>
     */
    public static function identity(): self
    {
        return new self(
            /**
             * @param I $s
             * @return I
             */
            static fn ($s) => $s,
            /**
             * @param I $_
             * @param I $a
             * @return I
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
     * @param B $b
     * @return T
     */
    public function set($s, $b)
    {
        return ($this->set)($s, $b);
    }

    /**
     * @param S $s
     * @param B $b
     * @return ResultInterface<T>
     */
    public function trySet($s, $b): ResultInterface
    {
        return wrap(fn () => ($this->set)($s, $b));
    }

    /**
     * @param S $s
     * @param callable(A): B $f
     * @return T
     */
    public function update($s, callable $f)
    {
        return $this->set($s, $f(($this->get)($s)));
    }

    /**
     * @param S $s
     * @param callable(A): B $f
     * @return ResultInterface<T>
     */
    public function tryUpdate($s, callable $f): ResultInterface
    {
        return wrap(fn () => $this->set($s, $f(($this->get)($s))));
    }

    /**
     * @return LensInterface<S|null, T|null, A|null, B|null>
     */
    public function optional(): LensInterface
    {
        return optional($this);
    }

    /**
     * @template A2
     * @template B2
     * @param LensInterface<A, B, A2, B2> $that
     * @return LensInterface<S, T, A2, B2>
     */
    public function compose(LensInterface $that): LensInterface
    {
        return new self(
            /**
             * @param S $s
             * @return A2
             */
            fn ($s) => $that->get(($this->get)($s)),
            /**
             * @param S $s
             * @param B2 $b2
             * @return T
             */
            fn ($s, $b2) => $this->set($s, $that->set($this->get($s), $b2))
        );
    }
}

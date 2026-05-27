<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Iso;

use Psl\Result\ResultInterface;
use VeeWee\Reflecta\Lens\Lens;
use VeeWee\Reflecta\Lens\LensInterface;
use function Psl\Result\wrap;

/**
 * @template S
 * @template T
 * @template A
 * @template B
 *
 * @psalm-immutable
 * @psalm-suppress ImpureFunctionCall
 * @implements IsoInterface<S, T, A, B>
 */
final class Iso implements IsoInterface
{
    /** @var callable(S): A */
    private $to;

    /** @var callable(B): T */
    private $from;

    /**
     * @param callable(S): A $to
     * @param callable(B): T $from
     */
    public function __construct(callable $to, callable $from)
    {
        $this->to = $to;
        $this->from = $from;
    }

    /**
     * @pure
     * @template I
     * @return Iso<I, I, I, I>
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
             * @returns I
             */
            static fn ($s) => $s
        );
    }

    /**
     * @param S $s
     * @return A
     */
    public function to($s)
    {
        return ($this->to)($s);
    }

    /**
     * @param S $s
     * @return ResultInterface<A>
     */
    public function tryTo($s): ResultInterface
    {
        return wrap(fn () => ($this->to)($s));
    }

    /**
     * @param B $b
     * @return T
     */
    public function from($b)
    {
        return ($this->from)($b);
    }

    /**
     * @param B $b
     * @return ResultInterface<T>
     */
    public function tryFrom($b): ResultInterface
    {
        return wrap(fn () => ($this->from)($b));
    }

    /**
     * @return Lens<S, T, A, B>
     */
    public function asLens(): LensInterface
    {
        return new Lens(
            $this->to,
            /**
             * @param S $_
             * @param B $b
             * @return T
             */
            fn ($_, $b) => $this->from($b)
        );
    }

    /**
     * @return Iso<B, A, T, S>
     */
    public function inverse(): self
    {
        return new self($this->from, $this->to);
    }

    /**
     * @template A2
     * @template B2
     * @param IsoInterface<A, B, A2, B2> $that
     * @return Iso<S, T, A2, B2>
     */
    public function compose(IsoInterface $that): IsoInterface
    {
        return new self(
            /**
             * @param S $s
             * @return A2
             */
            fn ($s) => $that->to($this->to($s)),
            /**
             * @param B2 $b2
             * @return T
             */
            fn ($b2) => $this->from($that->from($b2))
        );
    }
}

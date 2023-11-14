<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Optic;

use Psl\Result\ResultInterface;
use function Psl\Result\wrap;

/**
 * @template S
 * @template A
 *
 * @psalm-immutable
 * @psalm-suppress ImpureFunctionCall
 */
final class Iso
{
    /** @var callable(S): A */
    private $to;

    /** @var callable(A): S */
    private $from;

    /**
     * @param callable(S): A $to
     * @param callable(A): S $from
     */
    public function __construct(callable $to, callable $from)
    {
        $this->to = $to;
        $this->from = $from;
    }

    /**
     * @pure
     * @template I
     * @return Iso<I, I>
     */
    public static function id(): self
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
        return wrap(fn() => ($this->to)($s));
    }

    /**
     * @param A $a
     * @return S
     */
    public function from($a)
    {
        return ($this->from)($a);
    }

    /**
     * @param A $a
     * @return ResultInterface<S>
     */
    public function tryFrom($a): ResultInterface
    {
        return wrap(fn() => ($this->from)($a));
    }

    /**
     * @return Lens<S, A>
     */
    public function asLens(): Lens
    {
        return new Lens(
            $this->to,
            /**
             * @param S $_
             * @param A $a
             * @return S
             */
            fn($_, $a) => $this->from($a)
        );
    }

    /**
     * @template S2
     * @template A2
     * @param Iso<S2, A2> $that
     * @return Iso<S, A2>
     */
    public function compose(Iso $that): Iso
    {
        /** @psalm-suppress InvalidArgument */
        return new self(
            /**
             * @param S $s
             * @return A2
             */
            fn($s) => $that->to($this->to($s)),
            /**
             * @param A2 $a2
             * @return S
             */
            fn($a2) => $this->from($that->from($a2))
        );
    }
}

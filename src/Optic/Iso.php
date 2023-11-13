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
     * @param S $s
     * @return A
     */
    public function to($s)
    {
        return ($this->to)($s);
    }

    /**
     * @param A $b
     * @return S
     */
    public function from($b)
    {
        return ($this->from)($b);
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
             * @param A $b
             * @return S
             */
            fn($_, $b) => $this->from($b)
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
             * @param A2 $d
             * @return S
             */
            fn($d) => $this->from($that->from($d))
        );
    }
}

<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Iso;

use Psl\Result\ResultInterface;
use VeeWee\Reflecta\Lens\LensInterface;

/**
 * @template S
 * @template A
 *
 * @psalm-immutable
 */
interface IsoInterface
{
    /**
     * @param S $s
     * @return A
     */
    public function to($s);

    /**
     * @param S $s
     * @return ResultInterface<A>
     */
    public function tryTo($s): ResultInterface;

    /**
     * @param A $a
     * @return S
     */
    public function from($a);

    /**
     * @param A $a
     * @return ResultInterface<S>
     */
    public function tryFrom($a): ResultInterface;

    /**
     * @return LensInterface<S, A>
     */
    public function asLens(): LensInterface;

    /**
     * @return IsoInterface<A, S>
     */
    public function inverse(): self;

    /**
     * @template S2
     * @template A2
     * @param IsoInterface<S2, A2> $that
     * @return IsoInterface<S, A2>
     */
    public function compose(self $that): self;
}

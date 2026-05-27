<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Iso;

use Psl\Result\ResultInterface;
use VeeWee\Reflecta\Lens\LensInterface;

/**
 * @template-covariant S
 * @template-covariant T
 * @template-covariant A
 * @template-covariant B
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
     * @param B $b
     * @return T
     */
    public function from($b);

    /**
     * @param B $b
     * @return ResultInterface<T>
     */
    public function tryFrom($b): ResultInterface;

    /**
     * @return LensInterface<S, T, A, B>
     */
    public function asLens(): LensInterface;

    /**
     * @return IsoInterface<B, A, T, S>
     */
    public function inverse(): self;

    /**
     * @template A2
     * @template B2
     * @param IsoInterface<A, B, A2, B2> $that
     * @return IsoInterface<S, T, A2, B2>
     */
    public function compose(self $that): self;
}

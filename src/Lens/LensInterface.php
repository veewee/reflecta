<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

use Psl\Result\ResultInterface;

/**
 * @template-covariant S
 * @template-covariant T
 * @template-covariant A
 * @template-covariant B
 *
 * @psalm-immutable
 */
interface LensInterface
{
    /**
     * @param S $s
     * @return A
     */
    public function get($s);

    /**
     * @param S $s
     * @return ResultInterface<A>
     */
    public function tryGet($s): ResultInterface;

    /**
     * @param S $s
     * @param B $b
     * @return T
     */
    public function set($s, $b);

    /**
     * @param S $s
     * @param B $b
     * @return ResultInterface<T>
     */
    public function trySet($s, $b): ResultInterface;

    /**
     * @param S $s
     * @param callable(A): B $f
     * @return T
     */
    public function update($s, callable $f);

    /**
     * @param S $s
     * @param callable(A): B $f
     * @return ResultInterface<T>
     */
    public function tryUpdate($s, callable $f): ResultInterface;

    /**
     * @return LensInterface<S|null, T|null, A|null, B|null>
     */
    public function optional(): LensInterface;

    /**
     * @template A2
     * @template B2
     * @param LensInterface<A, B, A2, B2> $that
     * @return LensInterface<S, T, A2, B2>
     */
    public function compose(LensInterface $that): LensInterface;
}

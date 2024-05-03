<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Lens;

use Psl\Result\ResultInterface;

/**
 * @template S
 * @template A
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
     * @param A $a
     * @return S
     */
    public function set($s, $a);

    /**
     * @param S $s
     * @param A $a
     * @return ResultInterface<S>
     */
    public function trySet($s, $a): ResultInterface;

    /**
     * @param S $s
     * @param callable(A): A $f
     * @return S
     */
    public function update($s, callable $f);

    /**
     * @param S $s
     * @param callable(A): A $f
     * @return ResultInterface<S>
     */
    public function tryUpdate($s, callable $f): ResultInterface;

    /**
     * @return LensInterface<S, A|null>
     */
    public function optional(): LensInterface;

    /**
     * @template S2
     * @template A2
     * @param LensInterface<S2, A2> $that
     * @return LensInterface<S, A2>
     */
    public function compose(LensInterface $that): LensInterface;
}

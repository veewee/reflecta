<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Benchmarks;

use Generator;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\ParamProviders;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;
use stdClass;
use VeeWee\Reflecta\Iso\Iso;
use function Psl\Dict\from_keys;
use function Psl\Vec\map;
use function Psl\Vec\range;
use function VeeWee\Reflecta\Iso\object_data;

#[Revs(500)]
#[Iterations(10)]
#[Warmup(2)]
#[BeforeMethods('setUp')]
final class ObjectDataBench
{
    private Iso $iso;

    /**
     * @var array<string, int>
     */
    private array $values;

    /**
     * @param array{size: int} $params
     */
    public function setUp(array $params): void
    {
        $this->iso = object_data(stdClass::class);
        $this->values = from_keys(map(range(0, $params['size'] - 1), static fn (int $i): string => 'p' . $i), static fn (): int => 1);
    }

    #[ParamProviders(['provideSizes'])]
    public function bench_stdclass_from(): void
    {
        $this->iso->from($this->values);
    }

    public function provideSizes(): Generator
    {
        foreach ([5, 20, 100] as $size) {
            yield (string) $size => ['size' => $size];
        }
    }
}

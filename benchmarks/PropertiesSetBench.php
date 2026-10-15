<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Benchmarks;

use Generator;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\ParamProviders;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;
use stdClass;
use VeeWee\Reflecta\Benchmarks\Fixtures\InheritsPrivate100;
use VeeWee\Reflecta\Benchmarks\Fixtures\InheritsPrivate20;
use VeeWee\Reflecta\Benchmarks\Fixtures\InheritsPrivate5;
use VeeWee\Reflecta\Benchmarks\Fixtures\Typed100;
use VeeWee\Reflecta\Benchmarks\Fixtures\Typed20;
use VeeWee\Reflecta\Benchmarks\Fixtures\Typed5;
use function Psl\Dict\from_keys;
use function Psl\Vec\map;
use function Psl\Vec\range;
use function VeeWee\Reflecta\Reflect\properties_set;

#[Revs(500)]
#[Iterations(10)]
#[Warmup(2)]
#[BeforeMethods('setUp')]
final class PropertiesSetBench
{
    private const TYPED = [5 => Typed5::class, 20 => Typed20::class, 100 => Typed100::class];
    private const INHERITS_PRIVATE = [5 => InheritsPrivate5::class, 20 => InheritsPrivate20::class, 100 => InheritsPrivate100::class];

    private object $object;

    /**
     * @var array<string, int>
     */
    private array $values;

    /**
     * @param array{scenario: string, size: int} $params
     */
    public function setUp(array $params): void
    {
        $size = $params['size'];
        $allValues = from_keys(map(range(0, $size - 1), static fn (int $i): string => 'p' . $i), static fn (): int => 1);

        [$this->object, $this->values] = match ($params['scenario']) {
            'typed_all' => [new (self::TYPED[$size])(), $allValues],
            'typed_one' => [new (self::TYPED[$size])(), ['p0' => 1]],
            'stdclass_all_new' => [new stdClass(), $allValues],
            'parent_private' => [new (self::INHERITS_PRIVATE[$size])(), $allValues],
        };
    }

    #[ParamProviders(['provideScenarios', 'provideSizes'])]
    public function bench_properties_set(): void
    {
        properties_set($this->object, $this->values);
    }

    public function provideScenarios(): Generator
    {
        foreach (['typed_all', 'typed_one', 'stdclass_all_new', 'parent_private'] as $scenario) {
            yield $scenario => ['scenario' => $scenario];
        }
    }

    public function provideSizes(): Generator
    {
        foreach ([5, 20, 100] as $size) {
            yield (string) $size => ['size' => $size];
        }
    }
}

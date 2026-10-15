<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Benchmarks;

use Generator;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\ParamProviders;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;
use VeeWee\Reflecta\Benchmarks\Fixtures\Typed5;
use VeeWee\Reflecta\Lens\LensInterface;
use function VeeWee\Reflecta\Lens\index;
use function VeeWee\Reflecta\Lens\optional;
use function VeeWee\Reflecta\Lens\optional_index;
use function VeeWee\Reflecta\Lens\optional_property;
use function VeeWee\Reflecta\Lens\property;

#[Revs(1000)]
#[Iterations(10)]
#[Warmup(2)]
#[BeforeMethods('setUp')]
final class OptionalLensBench
{
    private LensInterface $lens;

    private mixed $subject;

    /**
     * @param array{lens: string, scenario: string} $params
     */
    public function setUp(array $params): void
    {
        [$subject, $name] = match ($params['scenario']) {
            'array_hit' => [['p0' => 1, 'p1' => 1], 'p0'],
            'array_miss' => [['p0' => 1, 'p1' => 1], 'missing'],
            'typed_hit' => [new Typed5(), 'p0'],
            'typed_miss' => [new Typed5(), 'missing'],
            'stdclass_hit' => [(object) ['p0' => 1, 'p1' => 1], 'p0'],
            'stdclass_miss' => [(object) ['p0' => 1, 'p1' => 1], 'missing'],
        };

        $this->subject = $subject;
        $this->lens = match ($params['lens']) {
            'optional_index' => optional(index($name)),
            'optional_index_fn' => optional_index($name),
            'optional_property' => optional(property($name)),
            'optional_property_fn' => optional_property($name),
        };
        $this->lens->get($this->subject);
    }

    #[ParamProviders(['provideIndexCases'])]
    public function bench_index_get(): void
    {
        $this->lens->get($this->subject);
    }

    #[ParamProviders(['providePropertyCases'])]
    public function bench_property_get(): void
    {
        $this->lens->get($this->subject);
    }

    public function provideIndexCases(): Generator
    {
        foreach (['array_hit', 'array_miss'] as $scenario) {
            yield $scenario . ' optional(index())' => ['lens' => 'optional_index', 'scenario' => $scenario];
            yield $scenario . ' optional_index()' => ['lens' => 'optional_index_fn', 'scenario' => $scenario];
        }
    }

    public function providePropertyCases(): Generator
    {
        foreach (['typed_hit', 'typed_miss', 'stdclass_hit', 'stdclass_miss'] as $scenario) {
            yield $scenario . ' optional(property())' => ['lens' => 'optional_property', 'scenario' => $scenario];
            yield $scenario . ' optional_property()' => ['lens' => 'optional_property_fn', 'scenario' => $scenario];
        }
    }
}

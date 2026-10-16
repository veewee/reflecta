<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Benchmarks;

use Closure;
use Generator;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\ParamProviders;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;
use VeeWee\Reflecta\Benchmarks\Fixtures\InheritsPrivate5;
use VeeWee\Reflecta\Benchmarks\Fixtures\Typed5;
use VeeWee\Reflecta\Benchmarks\Fixtures\Uninitialized5;
use function VeeWee\Reflecta\Lens\optional_property;
use function VeeWee\Reflecta\Reflect\property_is_initialized;

#[Revs(1000)]
#[Iterations(10)]
#[Warmup(2)]
#[BeforeMethods('setUp')]
final class PropertyIsInitializedBench
{
    private object $subject;

    /**
     * @var Closure(object): mixed
     */
    private Closure $check;

    /**
     * @param array{check: string, scenario: string} $params
     */
    public function setUp(array $params): void
    {
        [$subject, $name] = match ($params['scenario']) {
            'typed_hit' => [new Typed5(), 'p0'],
            'typed_uninitialized' => [new Uninitialized5(), 'p0'],
            'stdclass_hit' => [(object) ['p0' => 1, 'p1' => 1], 'p0'],
            'stdclass_miss' => [(object) ['p0' => 1, 'p1' => 1], 'missing'],
            'private_parent' => [new InheritsPrivate5(), 'p0'],
        };

        $this->subject = $subject;
        $this->check = match ($params['check']) {
            'optional_property' => optional_property($name)->get(...),
            'property_is_initialized' => static fn (object $subject): bool => property_is_initialized($subject, $name),
        };
        ($this->check)($this->subject);
    }

    #[ParamProviders(['provideCases'])]
    public function bench_check(): void
    {
        ($this->check)($this->subject);
    }

    public function provideCases(): Generator
    {
        foreach (['typed_hit', 'typed_uninitialized', 'stdclass_hit', 'stdclass_miss', 'private_parent'] as $scenario) {
            yield $scenario . ' optional_property()' => ['check' => 'optional_property', 'scenario' => $scenario];
            yield $scenario . ' property_is_initialized()' => ['check' => 'property_is_initialized', 'scenario' => $scenario];
        }
    }
}

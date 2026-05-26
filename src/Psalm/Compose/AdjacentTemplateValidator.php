<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Psalm\Compose;

use Psalm\CodeLocation;
use Psalm\Internal\Type\Comparator\UnionTypeComparator;
use Psalm\IssueBuffer;
use Psalm\Issue\InvalidArgument;
use Psalm\Plugin\EventHandler\Event\FunctionReturnTypeProviderEvent;
use Psalm\Type\Atomic\TGenericObject;
use Psalm\Type\Union;

use function count;
use function strtolower;

/**
 * @internal
 *
 * Since Iso/Lens templates are covariant, Psalm's template-inference based
 * argument check will silently widen the shared boundary type between
 * adjacent compose() args (e.g. accepting Iso<A,B>, Iso<C,C>, Iso<C,D>).
 *
 * This validator walks the adjacent arg pairs and emits InvalidArgument
 * when the right template of arg[i] is not equivalent to the left template
 * of arg[i+1].
 */
final class AdjacentTemplateValidator
{
    /**
     * @param class-string $genericClass Iso::class or Lens::class
     * @param non-empty-string $functionId
     */
    public static function validate(
        FunctionReturnTypeProviderEvent $event,
        string $genericClass,
        string $functionId,
    ): void {
        $args = $event->getCallArgs();
        if (count($args) < 2) {
            return;
        }

        $source = $event->getStatementsSource();
        $nodeTypes = $source->getNodeTypeProvider();
        $codebase = $source->getCodebase();
        $suppressed = $source->getSuppressedIssues();

        $genericClassLc = strtolower($genericClass);

        $previousRight = null;
        $previousIndex = 0;
        foreach ($args as $index => $arg) {
            $argType = $nodeTypes->getType($arg->value);
            if ($argType === null) {
                $previousRight = null;
                continue;
            }

            $generic = self::extractGeneric($argType, $genericClassLc);
            if ($generic === null) {
                $previousRight = null;
                continue;
            }

            [$left, $right] = $generic;

            if ($previousRight !== null) {
                $forward = UnionTypeComparator::isContainedBy($codebase, $previousRight, $left);
                $backward = UnionTypeComparator::isContainedBy($codebase, $left, $previousRight);

                if (!$forward || !$backward) {
                    IssueBuffer::maybeAdd(
                        new InvalidArgument(
                            'Argument ' . ($index + 1) . ' of ' . $functionId
                            . ' expects ' . $genericClass . '<' . $previousRight->getId() . ', ...>,'
                            . ' ' . $genericClass . '<' . $left->getId() . ', ...> provided'
                            . ' (compose boundary mismatch with argument ' . ($previousIndex + 1) . ')',
                            new CodeLocation($source, $arg->value),
                            $functionId,
                        ),
                        $suppressed,
                    );
                }
            }

            $previousRight = $right;
            $previousIndex = $index;
        }
    }

    /**
     * @param lowercase-string $genericClassLc
     * @return array{0: Union, 1: Union}|null
     */
    private static function extractGeneric(Union $argType, string $genericClassLc): ?array
    {
        foreach ($argType->getAtomicTypes() as $atomic) {
            if (!$atomic instanceof TGenericObject) {
                continue;
            }
            if (strtolower($atomic->value) !== $genericClassLc) {
                continue;
            }
            if (count($atomic->type_params) < 2) {
                continue;
            }

            return [$atomic->type_params[0], $atomic->type_params[1]];
        }

        return null;
    }
}

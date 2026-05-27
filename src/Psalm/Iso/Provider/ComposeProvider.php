<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Psalm\Iso\Provider;

use Psalm\Plugin\DynamicFunctionStorage;
use Psalm\Plugin\DynamicTemplateProvider;
use Psalm\Plugin\EventHandler\DynamicFunctionStorageProviderInterface;
use Psalm\Plugin\EventHandler\Event\DynamicFunctionStorageProviderEvent;
use Psalm\Storage\FunctionLikeParameter;
use Psalm\Type\Atomic\TGenericObject;
use Psalm\Type\Atomic\TTemplateParam;
use Psalm\Type\Union;

use VeeWee\Reflecta\Iso\Iso;
use function array_map;
use function count;
use function range;

final class ComposeProvider implements DynamicFunctionStorageProviderInterface
{
    private const FUNCTION_ID = 'veewee\reflecta\iso\compose';

    /**
     * @return array<lowercase-string>
     */
    public static function getFunctionIds(): array
    {
        return [self::FUNCTION_ID];
    }

    public static function getFunctionStorage(DynamicFunctionStorageProviderEvent $event): ?DynamicFunctionStorage
    {
        $templateProvider = $event->getTemplateProvider();
        $argsCount = count($event->getArgs());

        // No args: fall back to the declared signature (its non-empty-array param
        // already rejects empty calls). Building storage here would compute a
        // negative base offset and read undefined $templates entries.
        if ($argsCount === 0) {
            return null;
        }

        // STAB chain: for N args, we need 2N+2 templates.
        // Arg i (1..N) takes Iso<T(2i-1), T(2i), T(2i+1), T(2i+2)>.
        // Adjacent args share their (slot-3, slot-4) with the next arg's (slot-1, slot-2),
        // which under invariant templates forces the boundary types to unify.
        $templateCount = ($argsCount * 2) + 2;
        $templates = array_map(
            static fn (int $offset) => self::createTemplateFromOffset($templateProvider, $offset),
            range(1, $templateCount)
        );

        $composeStorage = new DynamicFunctionStorage();
        $composeStorage->templates = $templates;

        $params = [];
        foreach (range(1, $argsCount) as $argIndex) {
            $base = ($argIndex - 1) * 2;
            $params[] = self::createParam(
                "iso_{$argIndex}",
                new Union([
                    self::createStabIso(
                        $templates[$base],
                        $templates[$base + 1],
                        $templates[$base + 2],
                        $templates[$base + 3],
                    ),
                ]),
            );
        }
        $composeStorage->params = $params;

        // Return type: outer (S, T) of the first arg + inner (A, B) of the last arg.
        $composeStorage->return_type = new Union([
            self::createStabIso(
                $templates[0],
                $templates[1],
                $templates[$templateCount - 2],
                $templates[$templateCount - 1],
            ),
        ]);

        return $composeStorage;
    }

    private static function createTemplateFromOffset(
        DynamicTemplateProvider $template_provider,
        int $offset
    ): TTemplateParam {
        return $template_provider->createTemplate("T{$offset}");
    }

    private static function createStabIso(
        TTemplateParam $s,
        TTemplateParam $t,
        TTemplateParam $a,
        TTemplateParam $b,
    ): TGenericObject {
        return new TGenericObject(
            Iso::class,
            [
                new Union([$s]),
                new Union([$t]),
                new Union([$a]),
                new Union([$b]),
            ]
        );
    }

    private static function createParam(string $name, Union $type): FunctionLikeParameter
    {
        return new FunctionLikeParameter($name, false, $type);
    }
}

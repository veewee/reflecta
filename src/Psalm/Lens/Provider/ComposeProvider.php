<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Psalm\Lens\Provider;

use Closure;
use Psalm\Plugin\DynamicFunctionStorage;
use Psalm\Plugin\DynamicTemplateProvider;
use Psalm\Plugin\EventHandler\DynamicFunctionStorageProviderInterface;
use Psalm\Plugin\EventHandler\Event\DynamicFunctionStorageProviderEvent;
use Psalm\Storage\FunctionLikeParameter;
use Psalm\Type\Atomic\TClosure;
use Psalm\Type\Atomic\TGenericObject;
use Psalm\Type\Atomic\TTemplateParam;
use Psalm\Type\Union;

use VeeWee\Reflecta\Lens\Lens;
use function array_map;
use function count;
use function range;

final class ComposeProvider implements DynamicFunctionStorageProviderInterface
{
    /**
     * @return array<lowercase-string>
     */
    public static function getFunctionIds(): array
    {
        return ['veewee\reflecta\lens\compose'];
    }

    public static function getFunctionStorage(DynamicFunctionStorageProviderEvent $event): ?DynamicFunctionStorage
    {
        $templateProvider = $event->getTemplateProvider();
        $argsCount = count($event->getArgs());

        // Create S->A lens pairs
        $composedLenses = array_map(
            static fn(int $callable_offset) => self::createABLens(
                self::createTemplateFromOffset($templateProvider, $callable_offset),
                self::createTemplateFromOffset($templateProvider, $callable_offset + 1),
            ),
            range(1, $argsCount)
        );

        $composeStorage = new DynamicFunctionStorage();
        $composeStorage->params = [
            ...array_map(
                static fn(TGenericObject $lens, int $offset) => self::createParam(
                    "lens_{$offset}",
                    new Union([$lens]),
                ),
                $composedLenses,
                array_keys($composedLenses)
            )
        ];

        // Add compose template list for each intermediate Lens
        $composeStorage->templates = array_map(
            static fn($offset) => self::createTemplateFromOffset($templateProvider, $offset),
            range(1, $argsCount + 1),
        );

        // Compose return type from templates T1 -> TLast (Where TLast could also be T1 when no arguments are provided.)
        $composeStorage->return_type = new Union([
            self::createABLens(
                current($composeStorage->templates),
                end($composeStorage->templates)
            )
        ]);

        return $composeStorage;
    }

    private static function createTemplateFromOffset(
        DynamicTemplateProvider $template_provider,
        int $offset
    ): TTemplateParam {
        return $template_provider->createTemplate("T{$offset}");
    }

    private static function createABLens(
        TTemplateParam $aType,
        TTemplateParam $bType
    ): TGenericObject {
        return new TGenericObject(
            Lens::class,
            [
                new Union([$aType]),
                new Union([$bType]),
            ]
        );
    }

    private static function createParam(string $name, Union $type): FunctionLikeParameter
    {
        return new FunctionLikeParameter($name, false, $type);
    }
}

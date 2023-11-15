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
    /**
     * @return array<lowercase-string>
     */
    public static function getFunctionIds(): array
    {
        return ['veewee\reflecta\iso\compose'];
    }

    public static function getFunctionStorage(DynamicFunctionStorageProviderEvent $event): ?DynamicFunctionStorage
    {
        $templateProvider = $event->getTemplateProvider();
        $argsCount = count($event->getArgs());

        // Create S->A iso pairs
        $composedIsos = array_map(
            static fn (int $callable_offset) => self::createABIso(
                self::createTemplateFromOffset($templateProvider, $callable_offset),
                self::createTemplateFromOffset($templateProvider, $callable_offset + 1),
            ),
            range(1, $argsCount)
        );

        $composeStorage = new DynamicFunctionStorage();
        $composeStorage->params = [
            ...array_map(
                static fn (TGenericObject $iso, int $offset) => self::createParam(
                    "iso_{$offset}",
                    new Union([$iso]),
                ),
                $composedIsos,
                array_keys($composedIsos)
            )
        ];

        // Add compose template list for each intermediate Iso
        $composeStorage->templates = array_map(
            static fn ($offset) => self::createTemplateFromOffset($templateProvider, $offset),
            range(1, $argsCount + 1),
        );

        // Compose return type from templates T1 -> TLast (Where TLast could also be T1 when no arguments are provided.)
        $composeStorage->return_type = new Union([
            self::createABIso(
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

    private static function createABIso(
        TTemplateParam $aType,
        TTemplateParam $bType
    ): TGenericObject {
        return new TGenericObject(
            Iso::class,
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

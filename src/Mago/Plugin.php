<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Mago;

use Mago\Sdk\Analyzer\Plugin as PluginInterface;
use Mago\Sdk\Analyzer\PluginDefinition;
use Mago\Sdk\Analyzer\PluginRegistry;
use VeeWee\Reflecta\Mago\Iso\Provider\ComposeProvider as IsoComposeProvider;
use VeeWee\Reflecta\Mago\Lens\Provider\ComposeProvider as LensComposeProvider;
use VeeWee\Reflecta\Mago\Reflect\Provider\ObjectAttributesProvider;
use VeeWee\Reflecta\Mago\Reflect\Provider\PropertiesGetProvider;
use VeeWee\Reflecta\Mago\Reflect\Provider\PropertyGetProvider;

final class Plugin implements PluginInterface
{
    public function getDefinition(): PluginDefinition
    {
        return new PluginDefinition(
            'reflecta',
            'Reflecta',
            'Infers precise types for Reflecta optics and reflection helpers.',
        );
    }

    public function register(PluginRegistry $registry): void
    {
        $registry->registerFunctionReturnTypeProvider(new IsoComposeProvider());
        $registry->registerFunctionReturnTypeProvider(new LensComposeProvider());
        $registry->registerFunctionReturnTypeProvider(new PropertyGetProvider());
        $registry->registerFunctionReturnTypeProvider(new ObjectAttributesProvider());
        $registry->registerFunctionReturnTypeProvider(new PropertiesGetProvider());
    }
}

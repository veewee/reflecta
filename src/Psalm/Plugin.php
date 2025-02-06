<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Psalm;

use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;
use SimpleXMLElement;

final class Plugin implements PluginEntryPointInterface
{
    public function __invoke(RegistrationInterface $registration, ?SimpleXMLElement $config = null): void
    {
        foreach ($this->getHooks() as $hook) {
            /** @psalm-suppress UnresolvableInclude */
            require_once __DIR__ . '/' . str_replace([__NAMESPACE__, '\\'], ['', '/'], $hook) . '.php';

            $registration->registerHooksFromClass($hook);
        }
    }

    /**
     * @template T
     *
     * @return iterable<class-string>
     */
    private function getHooks(): iterable
    {
        yield Iso\Provider\ComposeProvider::class;
        yield Lens\Provider\ComposeProvider::class;
        yield Reflect\Provider\PropertiesSetProvider::class;
        yield Reflect\Provider\PropertiesGetProvider::class;
        yield Reflect\Provider\PropertyGetProvider::class;
        yield Reflect\Provider\PropertySetProvider::class;
    }
}

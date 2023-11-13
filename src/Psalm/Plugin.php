<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Psalm;

use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;
use SimpleXMLElement;

class Plugin implements PluginEntryPointInterface
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
        yield Provider\PropertyGetProvider::class;
    }
}

<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Mago\Lens\Provider;

use Mago\Sdk\Analyzer\FunctionReturnTypeProvider;
use Mago\Sdk\Analyzer\FunctionTarget;
use Mago\Sdk\Analyzer\ReturnTypeProviderContext;
use Mago\Sdk\Analyzer\Type;
use VeeWee\Reflecta\Lens\Lens;
use VeeWee\Reflecta\Mago\Optic\ComposedOpticType;

final class ComposeProvider implements FunctionReturnTypeProvider
{
    /**
     * @return non-empty-list<FunctionTarget>
     */
    public function getTargets(): array
    {
        return [FunctionTarget::exact('VeeWee\Reflecta\Lens\compose')];
    }

    public function getReturnType(ReturnTypeProviderContext $context): ?Type
    {
        return ComposedOpticType::infer($context->invocation, Lens::class);
    }
}

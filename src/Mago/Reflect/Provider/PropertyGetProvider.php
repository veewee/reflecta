<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Mago\Reflect\Provider;

use Mago\Sdk\Analyzer\FunctionReturnTypeProvider;
use Mago\Sdk\Analyzer\FunctionTarget;
use Mago\Sdk\Analyzer\ReturnTypeProviderContext;
use Mago\Sdk\Analyzer\Type;
use VeeWee\Reflecta\Mago\Reflect\Infer\ObjectType;
use VeeWee\Reflecta\Mago\Reflect\Infer\PropertyValueType;

final class PropertyGetProvider implements FunctionReturnTypeProvider
{
    /**
     * @return non-empty-list<FunctionTarget>
     */
    public function getTargets(): array
    {
        return [FunctionTarget::exact('VeeWee\Reflecta\Reflect\property_get')];
    }

    public function getReturnType(ReturnTypeProviderContext $context): ?Type
    {
        $class = ObjectType::infer($context->invocation->getArgument(0, 'object'));
        $name = PropertyValueType::inferName($context->invocation->getArgument(1, 'name'));

        if ($class === null || $name === null) {
            return null;
        }

        return PropertyValueType::infer($context->codebase, $class, $name);
    }
}

<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Mago\Reflect\Provider;

use Mago\Sdk\Analyzer\FunctionReturnTypeProvider;
use Mago\Sdk\Analyzer\FunctionTarget;
use Mago\Sdk\Analyzer\ReturnTypeProviderContext;
use Mago\Sdk\Analyzer\Type;

final class ObjectAttributesProvider implements FunctionReturnTypeProvider
{
    /**
     * @return non-empty-list<FunctionTarget>
     */
    public function getTargets(): array
    {
        return [
            FunctionTarget::exact('VeeWee\Reflecta\Reflect\object_attributes'),
            FunctionTarget::exact('VeeWee\Reflecta\Reflect\class_attributes'),
        ];
    }

    /**
     * Resolves the `(T is null ? list<object> : list<T>)` conditional return type.
     * Mago widens the `null` branch to `list<mixed>`, so only that branch is filled in here.
     */
    public function getReturnType(ReturnTypeProviderContext $context): ?Type
    {
        $attribute = $context->invocation->getArgument(1, 'attributeClassName');
        if ($attribute !== null && $attribute->type?->getLiteralClassString() !== null) {
            return null;
        }

        return Type::list(Type::object());
    }
}

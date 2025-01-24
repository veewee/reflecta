<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Psalm\Reflect\Provider;

use Psalm\Issue\UndefinedPropertyFetch;
use Psalm\IssueBuffer;
use Psalm\Plugin\DynamicFunctionStorage;
use Psalm\Plugin\EventHandler\DynamicFunctionStorageProviderInterface;
use Psalm\Plugin\EventHandler\Event\DynamicFunctionStorageProviderEvent;
use Psalm\Storage\FunctionLikeParameter;
use Psalm\Type\Union;
use VeeWee\Reflecta\Psalm\Reflect\Infer\ObjectType;
use VeeWee\Reflecta\Psalm\Reflect\Infer\PropertyNameType;
use VeeWee\Reflecta\Psalm\Reflect\Infer\PropertyValueType;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;

final class PropertyGetProvider implements DynamicFunctionStorageProviderInterface
{
    /**
     * @return array<lowercase-string>
     */
    public static function getFunctionIds(): array
    {
        return ['veewee\reflecta\reflect\property_get'];
    }

    public static function getFunctionStorage(DynamicFunctionStorageProviderEvent $event): ?DynamicFunctionStorage
    {
        $args = $event->getArgs();
        $inferrer = $event->getArgTypeInferer();

        $objectType = ObjectType::infer($inferrer, $args[0]);
        $propertyNameType = PropertyNameType::infer($inferrer, $args[1]);

        try {
            $inferredReturnType = PropertyValueType::infer($objectType, $propertyNameType);
        } catch (UnreflectableException $e) {
            IssueBuffer::maybeAdd(
                new UndefinedPropertyFetch(
                    $e->getMessage(),
                    $event->getCodeLocation(),
                    $objectType->value . '::' . $propertyNameType->value,
                ),
                $event->getStatementSource()->getSuppressedIssues()
            );

            return null;
        }

        if (!$objectType || !$propertyNameType || !$inferredReturnType) {
            return null;
        }

        $storage = new DynamicFunctionStorage();
        $storage->params = [
            new FunctionLikeParameter('object', false, new Union([$objectType])),
            new FunctionLikeParameter('name', false, new Union([$propertyNameType])),
        ];
        $storage->return_type = $inferredReturnType;

        return $storage;
    }
}

<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Psalm\Reflect\Provider;

use Psalm\Plugin\DynamicFunctionStorage;
use Psalm\Plugin\EventHandler\DynamicFunctionStorageProviderInterface;
use Psalm\Plugin\EventHandler\Event\DynamicFunctionStorageProviderEvent;
use Psalm\Storage\FunctionLikeParameter;
use Psalm\Type;
use Psalm\Type\Union;
use VeeWee\Reflecta\Psalm\Reflect\Infer\ObjectType;
use VeeWee\Reflecta\Psalm\Reflect\Infer\PropertiesValuesType;
use function array_key_exists;

final class PropertiesSetProvider implements DynamicFunctionStorageProviderInterface
{
    /**
     * @return array<lowercase-string>
     */
    public static function getFunctionIds(): array
    {
        return ['veewee\reflecta\reflect\properties_set'];
    }

    public static function getFunctionStorage(DynamicFunctionStorageProviderEvent $event): ?DynamicFunctionStorage
    {
        $args = $event->getArgs();
        $inferrer = $event->getArgTypeInferer();

        $objectType = ObjectType::infer($inferrer, $args[0]);
        $valuesType = PropertiesValuesType::infer($objectType, partial: true);
        $predicateType = array_key_exists(2, $args) ? $inferrer->infer($args[2]) : Type::getNull();

        if (!$objectType || !$valuesType) {
            return null;
        }

        $storage = new DynamicFunctionStorage();
        $storage->params = [
            new FunctionLikeParameter('object', false, new Union([$objectType])),
            new FunctionLikeParameter('values', false, $valuesType),
            new FunctionLikeParameter('predicate', false, $predicateType),
        ];
        $storage->return_type = new Union([$objectType]);

        return $storage;
    }
}

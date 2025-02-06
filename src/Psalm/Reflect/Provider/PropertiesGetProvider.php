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

final class PropertiesGetProvider implements DynamicFunctionStorageProviderInterface
{
    /**
     * @return array<lowercase-string>
     */
    public static function getFunctionIds(): array
    {
        return ['veewee\reflecta\reflect\properties_get'];
    }

    public static function getFunctionStorage(DynamicFunctionStorageProviderEvent $event): ?DynamicFunctionStorage
    {
        $args = $event->getArgs();
        $inferrer = $event->getArgTypeInferer();

        $objectType = ObjectType::infer($inferrer, $args[0]);
        $hasPredicate = array_key_exists(1, $args);
        $predicateType = $hasPredicate ? $inferrer->infer($args[1]) : Type::getNull();
        $valuesType = PropertiesValuesType::infer($objectType, partial: $hasPredicate);

        if (!$objectType || !$valuesType) {
            return null;
        }

        $storage = new DynamicFunctionStorage();
        $storage->params = [
            new FunctionLikeParameter('object', false, new Union([$objectType])),
            new FunctionLikeParameter('predicate', false, $predicateType),
        ];
        $storage->return_type = $valuesType;

        return $storage;
    }
}

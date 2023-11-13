<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Psalm\Provider;

use PhpParser\Node\Arg;
use Psalm\Internal\Codebase\Reflection;
use Psalm\Plugin\ArgTypeInferer;
use Psalm\Plugin\DynamicFunctionStorage;
use Psalm\Plugin\EventHandler\DynamicFunctionStorageProviderInterface;
use Psalm\Plugin\EventHandler\Event\DynamicFunctionStorageProviderEvent;
use Psalm\Storage\FunctionLikeParameter;
use Psalm\Type\Atomic\TLiteralString;
use Psalm\Type\Atomic\TNamedObject;
use Psalm\Type\Atomic\TTemplateParam;
use Psalm\Type\Union;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use function VeeWee\Reflecta\Reflect\reflect_property;

class PropertyGetProvider implements DynamicFunctionStorageProviderInterface
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

        $objectType = self::inferObjectType($inferrer, $args[0]);
        if (!$objectType) {
            return null;
        }

        $propertyNameType = self::inferPropertyNameType($inferrer, $args[1]);
        if (!$propertyNameType) {
            return null;
        }

        try {
            $prop = reflect_property($objectType->value, $propertyNameType->value);
        } catch (UnreflectableException) {
            return null;
        }

        $inferredReturnType = Reflection::getPsalmTypeFromReflectionType($prop->getType());

        $storage = new DynamicFunctionStorage();
        $storage->params = [
            new FunctionLikeParameter('object', false, new Union([$objectType])),
            new FunctionLikeParameter('name', false, new Union([$propertyNameType])),
        ];
        $storage->return_type = $inferredReturnType;


        return $storage;
    }

    private static function inferObjectType(ArgTypeInferer $inferer, Arg $arg): TNamedObject | TTemplateParam | null
    {
        $objectTypeUnion = $inferer->infer($arg);
        if (!$objectTypeUnion->isSingle()) {
            return null;
        }

        /** @var TNamedObject | TTemplateParam | null $objectType */
        $objectType = $objectTypeUnion->getSingleAtomic();
        if (!$objectType->isNamedObjectType()) {
            return null;
        }

        return $objectType;
    }

    private static function inferPropertyNameType(ArgTypeInferer $inferer, Arg $arg): TLiteralString | null
    {
        $propertyNameTypeUnion = $inferer->infer($arg);
        if (!$propertyNameTypeUnion->isSingleStringLiteral()) {
            return null;
        }

        return $propertyNameTypeUnion->getSingleStringLiteral();
    }
}

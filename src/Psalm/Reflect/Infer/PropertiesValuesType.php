<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Psalm\Reflect\Infer;

use Psalm\Internal\Codebase\Reflection;
use Psalm\Type;
use Psalm\Type\Atomic\TKeyedArray;
use Psalm\Type\Atomic\TNamedObject;
use Psalm\Type\Atomic\TTemplateParam;
use Psalm\Type\Union;
use ReflectionProperty;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;
use VeeWee\Reflecta\Reflect\Type\ReflectedProperty;
use function Psl\Dict\map;

final class PropertiesValuesType
{
    /**
     * @throws UnreflectableException
     */
    public static function infer(
        TNamedObject | TTemplateParam | null $objectType,
        bool $partial = false,
    ): Union|null {
        if (!$objectType) {
            return null;
        }

        $class = ReflectedClass::fromFullyQualifiedClassName($objectType->value);
        $properties = $class->properties();

        return new Union([
            new TKeyedArray(
                map(
                    $properties,
                    static fn (ReflectedProperty $prop) => Reflection::getPsalmTypeFromReflectionType($prop->apply(
                        static fn (ReflectionProperty $reflected) => $reflected->getType()
                    ))->setPossiblyUndefined($partial),
                ),
                fallback_params: $class->isDynamic() ? [Type::getArrayKey(), Type::getMixed()] : null,
            )
        ]);
    }
}

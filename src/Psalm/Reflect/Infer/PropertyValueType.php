<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Psalm\Reflect\Infer;

use Psalm\Internal\Codebase\Reflection;
use Psalm\Type\Atomic\TLiteralString;
use Psalm\Type\Atomic\TNamedObject;
use Psalm\Type\Atomic\TTemplateParam;
use Psalm\Type\Union;
use ReflectionProperty;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;

final class PropertyValueType
{
    public static function infer(
        TNamedObject | TTemplateParam | null $objectType,
        TLiteralString | null $propertyNameType
    ): Union|null {
        if (!$objectType || !$propertyNameType) {
            return null;
        }

        try {
            $prop = ReflectedClass::fromFullyQualifiedClassName($objectType->value)->property($propertyNameType->value);
        } catch (UnreflectableException $e) {
            return null;
        }

        return Reflection::getPsalmTypeFromReflectionType($prop->apply(
            static fn (ReflectionProperty $reflected) => $reflected->getType()
        ));
    }
}

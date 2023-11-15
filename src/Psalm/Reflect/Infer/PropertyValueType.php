<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Psalm\Reflect\Infer;

use Psalm\Internal\Codebase\Reflection;
use Psalm\Type\Atomic\TLiteralString;
use Psalm\Type\Atomic\TNamedObject;
use Psalm\Type\Atomic\TTemplateParam;
use Psalm\Type\Union;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use function VeeWee\Reflecta\Reflect\reflect_property;

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
            $prop = reflect_property($objectType->value, $propertyNameType->value);
        } catch (UnreflectableException $e) {
            return null;
        }

        return Reflection::getPsalmTypeFromReflectionType($prop->getType());
    }
}

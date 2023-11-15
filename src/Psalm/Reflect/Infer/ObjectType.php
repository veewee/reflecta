<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Psalm\Reflect\Infer;

use PhpParser\Node\Arg;
use Psalm\Plugin\ArgTypeInferer;
use Psalm\Type\Atomic\TNamedObject;
use Psalm\Type\Atomic\TTemplateParam;

final class ObjectType
{
    public static function infer(ArgTypeInferer $inferer, Arg $arg): TNamedObject | TTemplateParam | null
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
}

<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Psalm\Reflect\Infer;

use PhpParser\Node\Arg;
use Psalm\Plugin\ArgTypeInferer;
use Psalm\Type\Atomic\TLiteralString;

final class PropertyNameType
{
    public static function infer(ArgTypeInferer $inferer, Arg $arg): TLiteralString | null
    {
        $propertyNameTypeUnion = $inferer->infer($arg);
        if (!$propertyNameTypeUnion->isSingleStringLiteral()) {
            return null;
        }

        return $propertyNameTypeUnion->getSingleStringLiteral();
    }
}

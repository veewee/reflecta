<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Mago\Reflect\Infer;

use AllowDynamicProperties;
use Mago\Sdk\Analyzer\Codebase;
use Mago\Sdk\Analyzer\Metadata\AttributeMetadata;
use Mago\Sdk\Analyzer\Metadata\ClassLikeMetadata;
use Mago\Sdk\Analyzer\Type;
use Mago\Sdk\Analyzer\Type\ArrayItem;
use Mago\Sdk\Analyzer\Type\ArrayKey;
use Mago\Sdk\Analyzer\Type\ArrayKeyKind;
use Mago\Sdk\Analyzer\Type\KeyedArrayType;

use function Psl\Iter\any;
use function strcasecmp;

final class PropertiesValuesType
{
    /**
     * Shapes the array `properties_get()` hands back.
     *
     * A predicate makes every key possibly undefined, since which properties survive
     * the filter is only known at runtime. Classes accepting dynamic properties keep
     * an open `array-key => mixed` tail.
     */
    public static function infer(Codebase $codebase, string $class, bool $partial): ?Type
    {
        $metadata = $codebase->getClassLike($class);
        if ($metadata === null) {
            return null;
        }

        $items = [];
        foreach (ClassProperties::collect($codebase, $metadata) as $name => $type) {
            $items[] = new ArrayItem(new ArrayKey(ArrayKeyKind::String, $name), $partial, $type);
        }

        $dynamic = self::isDynamic($metadata);

        return Type::fromAtomic(new KeyedArrayType(
            $items === [] ? null : $items,
            $dynamic ? Type::union(Type::int(), Type::string()) : null,
            $dynamic ? Type::mixed() : null,
            false,
        ));
    }

    /**
     * Mago resolves attribute names to a fully qualified form without a leading
     * separator, but keeps them cased as written - and PHP class names are
     * case-insensitive, so `#[allowdynamicproperties]` is a legal spelling.
     */
    private static function isDynamic(ClassLikeMetadata $metadata): bool
    {
        return any(
            $metadata->attributes,
            static fn (AttributeMetadata $attribute): bool => strcasecmp(
                $attribute->name,
                AllowDynamicProperties::class,
            ) === 0,
        );
    }
}

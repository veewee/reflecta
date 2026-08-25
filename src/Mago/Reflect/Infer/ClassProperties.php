<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\Mago\Reflect\Infer;

use Mago\Sdk\Analyzer\Codebase;
use Mago\Sdk\Analyzer\Metadata\ClassLikeMetadata;
use Mago\Sdk\Analyzer\Metadata\MemberIdentifier;
use Mago\Sdk\Analyzer\Metadata\MetadataFlags;
use Mago\Sdk\Analyzer\Type;

use function array_reverse;
use function ltrim;

/**
 * Mirrors `ReflectedClass::properties()`: parent properties first (private ones
 * included), then the class' own, keyed by name so redeclarations win.
 */
final class ClassProperties
{
    /**
     * @return array<string, Type>
     */
    public static function collect(Codebase $codebase, ClassLikeMetadata $metadata): array
    {
        $properties = [];
        foreach ($codebase->getMultipleProperties(self::identifiers($codebase, $metadata)) as $property) {
            if ($property === null || $property->flags->contains(MetadataFlags::STATIC)) {
                continue;
            }

            $declared = $property->type ?? $property->declaredType;
            $properties[ltrim($property->name, characters: '$')] = $declared->type ?? Type::mixed();
        }

        return $properties;
    }

    /**
     * `ClassLikeMetadata::$properties` only lists the class' own declarations, so the
     * ancestors have to be walked. One batched lookup rather than one per ancestor:
     * every `Codebase` call crosses the worker protocol boundary.
     *
     * @return list<MemberIdentifier>
     */
    private static function identifiers(Codebase $codebase, ClassLikeMetadata $metadata): array
    {
        // `parentClasses` runs from the closest ancestor upwards; reverse it so the
        // most-derived declaration is the one that ends up in the resulting map.
        $names = [...array_reverse($metadata->parentClasses), $metadata->name];

        $identifiers = [];
        foreach ($codebase->getMultipleClassLikes($names) as $index => $classLike) {
            if ($classLike === null) {
                continue;
            }

            foreach ($classLike->properties as $property) {
                $identifiers[] = new MemberIdentifier($names[$index], $property);
            }
        }

        return $identifiers;
    }
}

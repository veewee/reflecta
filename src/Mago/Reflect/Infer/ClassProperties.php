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
     * @return list<MemberIdentifier>
     */
    private static function identifiers(Codebase $codebase, ClassLikeMetadata $metadata): array
    {
        // `parentClasses` runs from the closest ancestor upwards; reverse it so the
        // most-derived declaration is the one that ends up in the resulting map.
        $classes = [...array_reverse($metadata->parentClasses), $metadata->name];

        $identifiers = [];
        foreach ($classes as $class) {
            $classMetadata = $class === $metadata->name ? $metadata : $codebase->getClassLike($class);
            foreach ($classMetadata->properties ?? [] as $property) {
                $identifiers[] = new MemberIdentifier($class, $property);
            }
        }

        return $identifiers;
    }
}

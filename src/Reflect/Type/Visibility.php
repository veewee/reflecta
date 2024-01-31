<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect\Type;

use ReflectionProperty;

enum Visibility
{
    case Public;
    case Private;
    case Protected;

    /**
     * @pure
     */
    public static function forProperty(ReflectionProperty $property): self
    {
        return match (true) {
            $property->isPrivate() => self::Private,
            $property->isProtected() => self::Protected,
            $property->isPublic() => self::Public,
        };
    }
}

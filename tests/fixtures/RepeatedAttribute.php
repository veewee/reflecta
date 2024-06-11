<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\TestFixtures;

use Attribute;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class RepeatedAttribute
{
}

<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\TestFixtures;

use AllowDynamicProperties;

#[AllowDynamicProperties]
final class Dynamic
{
    public string $x;
}

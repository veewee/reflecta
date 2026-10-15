<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\TestFixtures\Optional;

use RuntimeException;

final class HookedSubject
{
    public string $hooked = 'raw' {
        get => 'hooked:' . $this->hooked;
    }

    public string $virtual {
        get => 'virtual';
    }

    public string $throwing {
        get => throw new RuntimeException('Hook failed');
    }
}

<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\TestFixtures\Optional;

final class MagicSubject
{
    public string $unset = 'unset';
    public string $uninitialized;

    public function __construct()
    {
        unset($this->unset);
    }

    public function __get(string $name): string
    {
        return 'magic:' . $name;
    }
}

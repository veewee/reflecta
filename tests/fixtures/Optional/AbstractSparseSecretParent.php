<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\TestFixtures\Optional;

abstract class AbstractSparseSecretParent
{
    private ?string $nullSecret = null;
    private string $uninitializedSecret;
}

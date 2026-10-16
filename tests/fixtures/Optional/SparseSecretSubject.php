<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\TestFixtures\Optional;

use AllowDynamicProperties;

#[AllowDynamicProperties]
final class SparseSecretSubject extends AbstractSparseSecretParent
{
    public readonly string $readonlyUninitialized;
}

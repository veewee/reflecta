<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\TestFixtures\Optional;

abstract class AbstractSecretParent
{
    protected string $inherited = 'parent-protected';
    private string $secret = 'parent-private';
}

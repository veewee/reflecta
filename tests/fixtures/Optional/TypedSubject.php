<?php
declare(strict_types=1);

namespace VeeWee\Reflecta\TestFixtures\Optional;

final class TypedSubject extends AbstractSecretParent
{
    public static string $static = 'static';

    public string $public = 'public';
    protected string $protected = 'protected';
    private string $private = 'private';
    public readonly string $readonly;
    public ?string $nullable = null;
    public string $uninitialized;
    public string $unset = 'unset';
    public $untypedUnset = 'untyped';

    public function __construct()
    {
        $this->readonly = 'readonly';
        unset($this->unset, $this->untypedUnset);
    }
}

<?php

namespace App\Domain\Entity\Trait;


/**
 * @method getKeyName()
 */
trait AuthenticatableUser
{
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    public function getAuthIdentifierName(): string
    {
        return $this->getKeyName();
    }

    public function getAuthPassword()
    {
        return $this->{$this->getAuthPasswordName()};
    }

    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    public function getRememberToken()
    {
    }

    public function setRememberToken($value)
    {
    }

    public function getRememberTokenName()
    {
    }
}

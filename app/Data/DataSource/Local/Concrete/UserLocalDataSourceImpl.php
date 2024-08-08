<?php

namespace App\Data\DataSource\Local\Concrete;

use App\Data\DataSource\Local\Abstract\UserLocalDataSource;
use App\Domain\Entity\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Hashing\HashManager;
use Override;
use SensitiveParameter;

class UserLocalDataSourceImpl implements UserLocalDataSource
{
    public function __construct(
        protected HashManager $hashManager,
    )
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findByUsernameAndPassword(
        string                       $username,
        #[SensitiveParameter] string $password,
    ): User
    {
        $user = User::where('username', $username)->first();
        if ($user && $this->hashManager->check($password, $user->password)) {
            return $user;
        }
        throw new ModelNotFoundException(User::class);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function find(int $at): User
    {
        return User::findOrFail($at);
    }
}

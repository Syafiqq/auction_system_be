<?php

namespace App\Data\Repository;

use App\Common\Exceptions\User\UserNotFoundException;
use App\Data\DataSource\Local\Abstract\UserLocalDataSource;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Override;
use SensitiveParameter;

class UserRepositoryImpl implements UserRepository
{
    public function __construct(
        protected UserLocalDataSource $localDataSource,
    )
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findByUsernameAndPasswordFromLocal(
        string                       $username,
        #[SensitiveParameter] string $password,
    ): User
    {
        try {
            return $this->localDataSource->findByUsernameAndPassword($username, $password);
        } catch (ModelNotFoundException) {
            throw new UserNotFoundException();
        }
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findFromLocal(int $at): User
    {
        return $this->localDataSource->find($at);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function updateAutobidToLocal(User $at, int $amount, int $percentage): User
    {
        return $this->localDataSource->updateAutobid($at, $amount, $percentage);
    }
}

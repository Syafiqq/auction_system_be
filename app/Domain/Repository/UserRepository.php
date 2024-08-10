<?php

namespace App\Domain\Repository;

use App\Common\Exceptions\User\UserNotFoundException;
use App\Domain\Entity\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use SensitiveParameter;

interface UserRepository
{
    /**
     * @param string $username
     * @param string $password
     * @return User
     * @throws UserNotFoundException
     */
    public function findByUsernameAndPasswordFromLocal(
        string                       $username,
        #[SensitiveParameter] string $password
    ): User;

    /**
     * @param int $at
     * @return User
     * @throws ModelNotFoundException<User>
     */
    public function findFromLocal(int $at): User;

    /**
     * @param User $at
     * @param int $amount
     * @param int $percentage
     * @return User
     * @throws ModelNotFoundException<User>
     */
    public function updateAutobidToLocal(User $at, int $amount, int $percentage): User;
}

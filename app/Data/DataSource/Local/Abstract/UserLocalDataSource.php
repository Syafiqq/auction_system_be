<?php

namespace App\Data\DataSource\Local\Abstract;


use App\Domain\Entity\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use SensitiveParameter;

interface UserLocalDataSource
{
    /**
     * @param string $username
     * @param string $password
     * @return User
     * @throws ModelNotFoundException<User>
     */
    public function findByUsernameAndPassword(
        string                       $username,
        #[SensitiveParameter] string $password
    ): User;

    /**
     * @param int $at
     * @return User
     * @throws ModelNotFoundException<User>
     */
    public function find(int $at): User;

    /**
     * @param User $at
     * @param int $amount
     * @param int $percentage
     * @return User
     * @throws ModelNotFoundException
     */
    public function updateAutobid(User $at, int $amount, int $percentage): User;
}

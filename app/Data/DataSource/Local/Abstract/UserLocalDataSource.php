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
}

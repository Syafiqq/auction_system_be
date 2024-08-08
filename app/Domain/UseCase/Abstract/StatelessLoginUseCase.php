<?php

namespace App\Domain\UseCase\Abstract;

use App\Common\Exceptions\User\UserNotFoundException;
use App\Domain\Entity\Dto\StatelessAuthenticatedUserResponseDto;
use SensitiveParameter;

interface StatelessLoginUseCase
{
    /**
     * @param string $username
     * @param string $password
     * @return StatelessAuthenticatedUserResponseDto
     * @throws UserNotFoundException
     */
    public function execute(
        string                       $username,
        #[SensitiveParameter] string $password
    ): StatelessAuthenticatedUserResponseDto;
}

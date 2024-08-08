<?php

namespace App\Domain\Entity\Dto;

use App\Domain\Entity\User;

class StatelessAuthenticatedUserResponseDto
{
    public function __construct(
        public User   $user,
        public string $accessToken,
    )
    {
    }
}

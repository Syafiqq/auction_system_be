<?php

namespace App\Domain\UseCase\Concrete;

use App\Domain\Entity\Dto\StatelessAuthenticatedUserResponseDto;
use App\Domain\Repository\UserRepository;
use App\Domain\UseCase\Abstract\StatelessLoginUseCase;
use SensitiveParameter;

class StatelessLoginUseCaseImpl implements StatelessLoginUseCase
{
    public function __construct(
        protected UserRepository $userRepository,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function execute(
        string                       $username,
        #[SensitiveParameter] string $password,
    ): StatelessAuthenticatedUserResponseDto
    {
        $user = $this->userRepository->findByUsernameAndPasswordFromLocal(
            $username,
            $password
        );
        $token = $user->createToken('access_token', ["role:{$user->type->value}"]);

        return new StatelessAuthenticatedUserResponseDto(
            $user,
            $token->plainTextToken
        );
    }
}

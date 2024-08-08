<?php

namespace App\Presentation\Http\Controllers;


use App\Common\Exceptions\User\UserNotFoundException;
use App\Domain\UseCase\Abstract\StatelessLoginUseCase;
use App\Presentation\Http\Requests\LoginRequest;
use App\Presentation\Http\Resources\UserResource;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    public function __construct(
        protected StatelessLoginUseCase $statelessLoginUseCase
    )
    {
    }


    /**
     * Store a newly created resource in storage.
     */
    public function statelessLogin(LoginRequest $request)
    {
        try {
            $response = $this->statelessLoginUseCase->execute(
                $request->username,
                $request->password
            );
        } catch (UserNotFoundException) {
            return response()->json(null, Response::HTTP_NOT_FOUND);
        } catch (Exception) {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return UserResource::new($response->user)
            ->withAccessToken($response->accessToken)
            ->toResponse($request);
    }
}

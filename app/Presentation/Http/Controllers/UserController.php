<?php

namespace App\Presentation\Http\Controllers;


use App\Domain\Repository\UserRepository;
use App\Presentation\Http\Requests\ProfileUpdateAutobidRequest;
use App\Presentation\Http\Resources\UserResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function __construct(
        protected UserRepository $userRepository
    )
    {
    }

    public function index(Request $request)
    {
        try {
            return UserResource::new($request->user())->toResponse($request);
        } catch (ModelNotFoundException) {
            return response()->json(null, Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception) {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateAutobid(ProfileUpdateAutobidRequest $request)
    {
        try {
            $response = $this->userRepository->updateAutobidToLocal(
                $request->user(),
                $request->amount,
                $request->percentage
            );
            return UserResource::new($response)->toResponse($request);
        } catch (Exception $e) {
            var_dump($e->getMessage());
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

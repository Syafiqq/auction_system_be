<?php

namespace App\Presentation\Http\Controllers;


use App\Presentation\Http\Resources\UserResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function __construct()
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
}

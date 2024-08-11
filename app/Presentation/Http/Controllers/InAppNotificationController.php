<?php

namespace App\Presentation\Http\Controllers;


use App\Domain\Repository\InAppNotificationRepository;
use App\Presentation\Http\Requests\InAppNotificationListRequest;
use App\Presentation\Http\Resources\AbstractPaginatorResourceCollection;
use App\Presentation\Http\Resources\InAppNotificationResource;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class InAppNotificationController
{
    public function __construct(
        protected InAppNotificationRepository $inAppNotificationRepository
    )
    {
    }

    public function index(InAppNotificationListRequest $request)
    {
        try {
            $response = $this->inAppNotificationRepository->findPaginatedFromLocal(
                $request->user()->id,
                $request->page ?? 1,
                10,
            );
            return AbstractPaginatorResourceCollection::new($response)
                ->withMorph(fn($collection) => InAppNotificationResource::collection($collection))
                ->toResponse($request);
        } catch (Exception $exception) {
            var_dump($exception->getMessage());
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

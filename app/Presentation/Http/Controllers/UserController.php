<?php

namespace App\Presentation\Http\Controllers;


use App\Domain\Entity\Dto\AuctionItemOwnedUserSearchRequestDto;
use App\Domain\Entity\Dto\AuctionItemWinnerSearchRequestDto;
use App\Domain\Repository\AuctionItemRepository;
use App\Domain\Repository\UserRepository;
use App\Presentation\Http\Requests\AuctionItemOwnedListRequest;
use App\Presentation\Http\Requests\AuctionItemWinnerRequest;
use App\Presentation\Http\Requests\ProfileUpdateAutobidRequest;
use App\Presentation\Http\Resources\AbstractPaginatorResourceCollection;
use App\Presentation\Http\Resources\AuctionItemOwnedResource;
use App\Presentation\Http\Resources\AuctionItemWinnerResource;
use App\Presentation\Http\Resources\UserResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function __construct(
        protected UserRepository        $userRepository,
        protected AuctionItemRepository $auctionItemRepository
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
        } catch (Exception) {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function auctions(AuctionItemOwnedListRequest $request)
    {
        try {
            $response = $this->auctionItemRepository->findOwnedUserPaginatedFromLocal(
                new AuctionItemOwnedUserSearchRequestDto(
                    $request->user()->id,
                    $request->name,
                    $request->description,
                    $request->types
                ),
                $request->page ?? 1,
                10,
            );
            return AbstractPaginatorResourceCollection::new($response)
                ->withMorph(fn($collection) => AuctionItemOwnedResource::collection($collection))
                ->toResponse($request);
        } catch (Exception) {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function winner(AuctionItemWinnerRequest $request)
    {
        try {
            $response = $this->auctionItemRepository->findWinnerUserPaginatedFromLocal(
                new AuctionItemWinnerSearchRequestDto(
                    $request->user()->id,
                    $request->name,
                    $request->description
                ),
                $request->page ?? 1,
                10,
            );
            return AbstractPaginatorResourceCollection::new($response)
                ->withMorph(fn($collection) => AuctionItemWinnerResource::collection($collection))
                ->toResponse($request);
        } catch (Exception) {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

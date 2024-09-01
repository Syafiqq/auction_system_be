<?php

namespace App\Presentation\Http\Controllers;


use App\Domain\Entity\Dto\AuctionItemCreateRequestDto;
use App\Domain\Entity\Dto\AuctionItemSearchRequestDto;
use App\Domain\Entity\Dto\AuctionItemUpdateRequestDto;
use App\Domain\Entity\Dto\AuctionItemWinnerRequestDto;
use App\Domain\Repository\AuctionItemRepository;
use App\Domain\Repository\BillRepository;
use App\Presentation\Http\Requests\AuctionItemBillRequest;
use App\Presentation\Http\Requests\AuctionItemCreateRequest;
use App\Presentation\Http\Requests\AuctionItemDeleteRequest;
use App\Presentation\Http\Requests\AuctionItemDetailRequest;
use App\Presentation\Http\Requests\AuctionItemListRequest;
use App\Presentation\Http\Requests\AuctionItemPayBillRequest;
use App\Presentation\Http\Requests\AuctionItemUpdateRequest;
use App\Presentation\Http\Requests\AuctionUpdateAutobidRequest;
use App\Presentation\Http\Resources\AbstractPaginatorResourceCollection;
use App\Presentation\Http\Resources\AuctionItemBillResource;
use App\Presentation\Http\Resources\AuctionItemResource;
use App\Presentation\Http\Resources\BillResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuctionItemController
{
    public function __construct(
        protected AuctionItemRepository $auctionItemRepository,
        protected BillRepository        $billRepository
    )
    {
    }

    public function index(AuctionItemListRequest $request)
    {
        try {
            $response = $this->auctionItemRepository->findPaginatedFromLocal(
                new AuctionItemSearchRequestDto(
                    $request->name,
                    $request->description,
                    $request->price_order == null
                        ? null
                        : $request->price_order == 'asc'
                ),
                $request->page ?? 1,
                10,
            );
            return AbstractPaginatorResourceCollection::new($response)
                ->withMorph(fn($collection) => AuctionItemResource::collection($collection))
                ->toResponse($request);
        } catch (Exception) {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(AuctionItemCreateRequest $request)
    {
        try {
            $response = $this->auctionItemRepository->insertToLocal(
                new AuctionItemCreateRequestDto(
                    $request->name,
                    $request->description,
                    $request->starting_price,
                    $request->end_time,
                    $request->images,
                    []
                ),
            );
            return AuctionItemResource::new($response)->toResponse($request);
        } catch (Exception) {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(AuctionItemDetailRequest $request)
    {
        try {
            $response = $this->auctionItemRepository->findForFromLocal(
                $request->id,
                $request->user()->id,
            );
            return AuctionItemResource::new($response)->toResponse($request);
        } catch (ModelNotFoundException) {
            return response()->json(null, Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception) {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(AuctionItemUpdateRequest $request)
    {
        try {
            $response = $this->auctionItemRepository->updateFromLocal(
                new AuctionItemUpdateRequestDto(
                    $request->name,
                    $request->description,
                    $request->starting_price,
                    $request->end_time,
                    $request->retained_old_images ?? [],
                    $request->images ?? [],
                    []
                ),
                $request->id,
            );
            return AuctionItemResource::new($response)->toResponse($request);
        } catch (ModelNotFoundException) {
            return response()->json(null, Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception) {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(AuctionItemDeleteRequest $request)
    {
        try {
            $response = $this->auctionItemRepository->deleteToLocal(
                $request->id,
            );
            return AuctionItemResource::new($response)->toResponse($request);
        } catch (ModelNotFoundException) {
            return response()->json(null, Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception|Throwable) {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function autobidUpdate(AuctionUpdateAutobidRequest $request)
    {
        try {
            $response = $this->auctionItemRepository->updateAutobidToLocal(
                $request->id,
                $request->user()->id,
                $request->is_autobid,
            );
            return AuctionItemResource::new($response)->toResponse($request);
        } catch (ModelNotFoundException) {
            return response()->json(null, Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception|Throwable) {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function bill(AuctionItemBillRequest $request)
    {
        try {
            $response = $this->auctionItemRepository->findWinnerFromLocal(
                new AuctionItemWinnerRequestDto(
                    $request->user()->id,
                    $request->id,
                )
            );
            return AuctionItemBillResource::new($response)->toResponse($request);
        } catch (ModelNotFoundException) {
            return response()->json(null, Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception) {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function pay(AuctionItemPayBillRequest $request)
    {
        try {
            $response = $this->billRepository->payToLocal(
                $request->user()->id,
                $request->id,
                $request->bid,
            );
            return BillResource::new($response)->toResponse($request);
        } catch (ModelNotFoundException) {
            return response()->json(null, Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception) {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

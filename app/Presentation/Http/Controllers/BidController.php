<?php

namespace App\Presentation\Http\Controllers;


use App\Common\Exceptions\Bid\AuctionEndedEarlyException;
use App\Common\Exceptions\Bid\AuctionEndedException;
use App\Common\Exceptions\Bid\LessBidPlacedException;
use App\Common\Exceptions\Bid\NewerBidPresentException;
use App\Domain\Entity\Dto\BidRequestDto;
use App\Domain\UseCase\Abstract\PlaceManualBidUseCase;
use App\Presentation\Http\Requests\PlaceBidRequest;
use App\Presentation\Http\Resources\BidResource;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class BidController
{
    public function __construct(
        protected PlaceManualBidUseCase $placeBidUseCase
    )
    {
    }

    public function place(PlaceBidRequest $request)
    {
        try {
            $response = $this->placeBidUseCase->execute(
                new BidRequestDto(
                    $request->user(),
                    $request->id,
                    $request->amount,
                    $request->last_bid_reference,
                )
            );
            return BidResource::new($response)->toResponse($request);
        } catch (NewerBidPresentException $exception) {
            return response()->json([
                'message' => 'There is a new bid placed, please review your bid',
                'data' => BidResource::new($exception->bid)->toArray($request),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (LessBidPlacedException $exception) {
            return response()->json([
                'message' => "Your bid is lower than or equal {$exception->amount}, please review your bid"
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (AuctionEndedException $exception) {
            return response()->json([
                'message' => "Auction has ended at {$exception->date->format('Y-m-d H:i:s')}"
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (AuctionEndedEarlyException) {
            return response()->json([
                'message' => "Auction has ended early, cannot place bid anymore"
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception) {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

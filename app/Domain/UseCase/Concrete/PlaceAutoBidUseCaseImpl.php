<?php

namespace App\Domain\UseCase\Concrete;

use App\Common\Exceptions\Bid\InsufficientAutobidException;
use App\Domain\Entity\Bid;
use App\Domain\Entity\Dto\BidRequestDto;
use App\Domain\Entity\Enum\BidTypeEnum;
use App\Domain\Repository\AuctionItemRepository;
use App\Domain\Repository\BidRepository;
use App\Domain\Repository\UserRepository;
use App\Domain\UseCase\Abstract\PlaceAutoBidUseCase;
use App\Domain\UseCase\Trait\PlaceBidPreparation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Override;

class PlaceAutoBidUseCaseImpl implements PlaceAutoBidUseCase
{
    use PlaceBidPreparation;

    public function __construct(
        protected UserRepository        $userRepository,
        protected BidRepository         $bidRepository,
        protected AuctionItemRepository $auctionItemRepository,
    )
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function execute(int $for, int $at): void
    {
        $user = $this->userRepository->findFromLocal($for);

        $auction = $this->auctionItemRepository->findFromLocal($at);

        $usage = $this->bidRepository->getAutobidUsageFromLocal($user, $at);
        $balance = $user->autobid_capacity - $usage;

        /** @var ?Bid $bid */
        $bid = null;
        try {
            $bid = $this->bidRepository->findNewestFromLocal($at);
        } catch (ModelNotFoundException) {
        }

        $amount = $auction->starting_price + 1;
        if ($bid != null) {
            $amount = $bid->amount + 1;
        }

        if ($balance < $amount) {
            $this->auctionItemRepository->updateAutobidToLocal($at, $for, false);
            throw new InsufficientAutobidException($balance, $amount);
        }

        $data = new BidRequestDto(
            $user,
            $at,
            $amount,
            null
        );

        $this->validate($auction, $bid, $data);

        $this->bidRepository->insertToLocal($data, BidTypeEnum::auto);
    }
}

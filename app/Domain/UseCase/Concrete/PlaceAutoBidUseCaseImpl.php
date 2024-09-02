<?php

namespace App\Domain\UseCase\Concrete;

use App\Common\Exceptions\Bid\InsufficientAutobidException;
use App\Domain\Entity\Bid;
use App\Domain\Entity\Dto\BidRequestDto;
use App\Domain\Entity\Enum\BidTypeEnum;
use App\Domain\Entity\Enum\NotificationType;
use App\Domain\Repository\AuctionItemRepository;
use App\Domain\Repository\BidRepository;
use App\Domain\Repository\InAppNotificationRepository;
use App\Domain\Repository\UserRepository;
use App\Domain\UseCase\Abstract\PlaceAutoBidUseCase;
use App\Domain\UseCase\Trait\PlaceBidPreparation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use NumberFormatter;
use Override;

class PlaceAutoBidUseCaseImpl implements PlaceAutoBidUseCase
{
    use PlaceBidPreparation;

    public function __construct(
        protected UserRepository              $userRepository,
        protected BidRepository               $bidRepository,
        protected AuctionItemRepository       $auctionItemRepository,
        protected InAppNotificationRepository $inAppNotificationRepository,
    )
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function execute(int $for, int $at): void
    {
        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);

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
            $this->inAppNotificationRepository->insertToLocal(
                NotificationType::insufficientAutoBidBalance->builder(
                    1,
                    [
                        'for' => $for,
                        'at' => $at,
                        'balance' => $numberFormatter->formatCurrency($balance, 'USD'),
                        'price' => $numberFormatter->formatCurrency($amount, 'USD'),
                    ]
                )
            );
            throw new InsufficientAutobidException($balance, $amount);
        }

        if ($user->autobid_percentage_warning != null &&
            ($amount + $usage) > max(($user->autobid_capacity * $user->autobid_percentage_warning / 100), 0)) {
            $percentage = ($amount + $usage) * 100 / $user->autobid_capacity;
            $this->inAppNotificationRepository->insertToLocal(
                NotificationType::autobidUsageWarning->builder(
                    1,
                    [
                        'for' => $for,
                        'at' => $at,
                        'usage' => $percentage,
                    ]
                )
            );
        }

        $data = new BidRequestDto(
            $user,
            $at,
            $amount,
            null
        );

        $this->validate($auction, $bid, $data);

        $bid = $this->bidRepository->insertToLocal($data, BidTypeEnum::auto);
        $this->inAppNotificationRepository->insertToLocal(
            NotificationType::autobidPlaced->builder(
                1,
                [
                    'for' => $bid->user_id,
                    'at' => $bid->auction_item_id,
                    'name' => $auction->name,
                    'price' => $numberFormatter->formatCurrency($bid->amount, 'USD'),
                ]
            )
        );
    }
}

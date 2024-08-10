<?php

namespace App\Domain\UseCase\Abstract;

interface SetAuctionWinnerUseCase
{
    /**
     * @return void
     */
    public function execute(): void;
}

<?php

namespace App\Providers;

use App\Data\DataSource\Local\Abstract\AuctionItemLocalDataSource;
use App\Data\DataSource\Local\Abstract\BidLocalDataSource;
use App\Data\DataSource\Local\Abstract\UserLocalDataSource;
use App\Data\DataSource\Local\Concrete\AuctionItemLocalDataSourceImpl;
use App\Data\DataSource\Local\Concrete\BidLocalDataSourceImpl;
use App\Data\DataSource\Local\Concrete\UserLocalDataSourceImpl;
use App\Data\Repository\AuctionItemRepositoryImpl;
use App\Data\Repository\BidRepositoryImpl;
use App\Data\Repository\UserRepositoryImpl;
use App\Domain\Repository\AuctionItemRepository;
use App\Domain\Repository\BidRepository;
use App\Domain\Repository\UserRepository;
use App\Domain\UseCase\Abstract\PlaceAutoBidUseCase;
use App\Domain\UseCase\Abstract\PlaceManualBidUseCase;
use App\Domain\UseCase\Abstract\SetAuctionWinnerUseCase;
use App\Domain\UseCase\Abstract\StatelessLoginUseCase;
use App\Domain\UseCase\Concrete\PlaceAutoBidUseCaseImpl;
use App\Domain\UseCase\Concrete\PlaceManualBidUseCaseImpl;
use App\Domain\UseCase\Concrete\SetAuctionWinnerUseCaseImpl;
use App\Domain\UseCase\Concrete\StatelessLoginUseCaseImpl;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserLocalDataSource::class, UserLocalDataSourceImpl::class);
        $this->app->singleton(AuctionItemLocalDataSource::class, AuctionItemLocalDataSourceImpl::class);
        $this->app->singleton(BidLocalDataSource::class, BidLocalDataSourceImpl::class);

        $this->app->singleton(UserRepository::class, UserRepositoryImpl::class);
        $this->app->singleton(AuctionItemRepository::class, AuctionItemRepositoryImpl::class);
        $this->app->singleton(BidRepository::class, BidRepositoryImpl::class);

        $this->app->bind(StatelessLoginUseCase::class, StatelessLoginUseCaseImpl::class);
        $this->app->bind(PlaceManualBidUseCase::class, PlaceManualBidUseCaseImpl::class);
        $this->app->bind(SetAuctionWinnerUseCase::class, SetAuctionWinnerUseCaseImpl::class);
        $this->app->bind(PlaceAutoBidUseCase::class, PlaceAutoBidUseCaseImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}

<?php

namespace App\Providers;

use App\Data\DataSource\Local\Abstract\AuctionItemLocalDataSource;
use App\Data\DataSource\Local\Abstract\AuctionWinnerMailJobLocalDataSource;
use App\Data\DataSource\Local\Abstract\AutobidJobLocalDataSource;
use App\Data\DataSource\Local\Abstract\BidLocalDataSource;
use App\Data\DataSource\Local\Abstract\BillLocalDataSource;
use App\Data\DataSource\Local\Abstract\InAppNotificationLocalDataSource;
use App\Data\DataSource\Local\Abstract\UserLocalDataSource;
use App\Data\DataSource\Local\Concrete\AuctionItemLocalDataSourceImpl;
use App\Data\DataSource\Local\Concrete\AuctionWinnerMailJobLocalDataSourceImpl;
use App\Data\DataSource\Local\Concrete\AutobidJobLocalDataSourceImpl;
use App\Data\DataSource\Local\Concrete\BidLocalDataSourceImpl;
use App\Data\DataSource\Local\Concrete\BillILocalDataSourceImpl;
use App\Data\DataSource\Local\Concrete\InAppNotificationLocalDataSourceImpl;
use App\Data\DataSource\Local\Concrete\UserLocalDataSourceImpl;
use App\Data\Repository\AuctionItemRepositoryImpl;
use App\Data\Repository\AuctionWinnerMailJobRepositoryImpl;
use App\Data\Repository\AutobidJobRepositoryImpl;
use App\Data\Repository\BidRepositoryImpl;
use App\Data\Repository\BillRepositoryImpl;
use App\Data\Repository\InAppNotificationRepositoryImpl;
use App\Data\Repository\UserRepositoryImpl;
use App\Domain\Repository\AuctionItemRepository;
use App\Domain\Repository\AuctionWinnerMailJobRepository;
use App\Domain\Repository\AutobidJobRepository;
use App\Domain\Repository\BidRepository;
use App\Domain\Repository\BillRepository;
use App\Domain\Repository\InAppNotificationRepository;
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
        $this->app->singleton(InAppNotificationLocalDataSource::class, InAppNotificationLocalDataSourceImpl::class);
        $this->app->singleton(AutobidJobLocalDataSource::class, AutobidJobLocalDataSourceImpl::class);
        $this->app->singleton(BillLocalDataSource::class, BillILocalDataSourceImpl::class);
        $this->app->singleton(AuctionWinnerMailJobLocalDataSource::class, AuctionWinnerMailJobLocalDataSourceImpl::class);

        $this->app->singleton(UserRepository::class, UserRepositoryImpl::class);
        $this->app->singleton(AuctionItemRepository::class, AuctionItemRepositoryImpl::class);
        $this->app->singleton(BidRepository::class, BidRepositoryImpl::class);
        $this->app->singleton(InAppNotificationRepository::class, InAppNotificationRepositoryImpl::class);
        $this->app->singleton(AutobidJobRepository::class, AutobidJobRepositoryImpl::class);
        $this->app->singleton(BillRepository::class, BillRepositoryImpl::class);
        $this->app->singleton(AuctionWinnerMailJobRepository::class, AuctionWinnerMailJobRepositoryImpl::class);

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

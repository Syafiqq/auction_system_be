<?php

namespace App\Providers;

use App\Data\DataSource\Local\Abstract\UserLocalDataSource;
use App\Data\DataSource\Local\Concrete\UserLocalDataSourceImpl;
use App\Data\Repository\UserRepositoryImpl;
use App\Domain\Repository\UserRepository;
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

        $this->app->singleton(UserRepository::class, UserRepositoryImpl::class);
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

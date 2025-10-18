<?php

namespace App\Providers;

use App\Facades\ApiResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Facades\FacadesLogic\ApiResponseLogic;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ApiResponse::class,
            ApiResponseLogic::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        Route::middleware(['api'])
            ->prefix('api/v1')
            ->group(base_path('routes/V1/api.php'));

        Route::middleware(['api'])
            ->prefix('api/v1/enums')
            ->group(base_path('routes/V1/enums.php'));
        
        Route::middleware(['api', 'domainExists'])
            ->prefix('api/v1/platform')
            ->group(base_path('routes/V1/platform.php'));

        Route::middleware(['api', 'domainExists'])
            ->prefix('api/v1/dashboard')
            ->group(base_path('routes/V1/dashboard.php'));
    }
}

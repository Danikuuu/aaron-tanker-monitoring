<?php

namespace App\Providers;

use App\Repositories\Admin\AnalyticsInterface;
use App\Repositories\Admin\AnalyticsRepository;
use App\Repositories\Admin\FuelSummaryInterface;
use App\Repositories\Admin\FuelSummaryRepository;
use App\Repositories\Admin\OverviewInterface;
use App\Repositories\Admin\OverviewRepository;
use App\Repositories\Admin\ReceiptInterface;
use App\Repositories\Admin\ReceiptRepository;
use App\Repositories\Admin\TransactionHistoryInterface;
use App\Repositories\Admin\TransactionHistoryRepository;
use App\Repositories\Auth\LoginInterface;
use App\Repositories\Auth\LoginRepository;
use App\Repositories\Auth\RegisterInterface;
use App\Repositories\Auth\RegisterRepository;
use App\Repositories\Staff\FuelSupplyRepository;
use App\Repositories\Staff\FuelSupplyRepositoryInterface;
use App\Repositories\Staff\TankerArrivalRepository;
use App\Repositories\Staff\TankerArrivalRepositoryInterface;
use App\Repositories\Staff\TankerDepartureRepository;
use App\Repositories\Staff\TankerDepartureRepositoryInterface;
use App\Repositories\Staff\TankerHistoryRepository;
use App\Repositories\Staff\TankerHistoryRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            LoginInterface::class,
            LoginRepository::class
        );

        $this->app->bind(
            RegisterInterface::class,
            RegisterRepository::class
        );

        $this->app->bind(
            OverviewInterface::class,
            OverviewRepository::class
        );

        $this->app->bind(
            AnalyticsInterface::class,
            AnalyticsRepository::class
        );


        $this->app->bind(
            FuelSummaryInterface::class, 
            FuelSummaryRepository::class
        );

        $this->app->bind(
            TransactionHistoryInterface::class, 
            TransactionHistoryRepository::class
        );

        $this->app->bind(
            ReceiptInterface::class, 
            ReceiptRepository::class
        );

        $this->app->bind(
            TankerArrivalRepositoryInterface::class,
            TankerArrivalRepository::class
        );

        
        $this->app->bind(
            TankerDepartureRepositoryInterface::class,
            TankerDepartureRepository::class
        );

        $this->app->bind(
            FuelSupplyRepositoryInterface::class,
            FuelSupplyRepository::class
        );

        $this->app->bind(
            TankerHistoryRepositoryInterface::class,
            TankerHistoryRepository::class
        );

    }

    public function boot(): void
    {
        //
    }
}

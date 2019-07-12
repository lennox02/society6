<?php

namespace App\Providers;

use App\Repositories\CreativesRepository;
use App\Repositories\Interfaces\CreativesRepositoryInterface;
use App\Repositories\OrderProductsRepository;
use App\Repositories\Interfaces\OrderProductsRepositoryInterface;
use App\Repositories\VendorRepository;
use App\Repositories\Interfaces\VendorRepositoryInterface;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider implements DeferrableProvider
{

    /**
     * All of the container bindings that should be registered.
     *
     * @var array
    */
    public $bindings = [
        CreativesRepositoryInterface::class => CreativesRepository::class,
        OrderProductsRepositoryInterface::class => OrderProductsRepository::class,
        VendorRepositoryInterface::class => VendorRepository::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /*
        $this->app->bind(
            CreativesRepositoryInterface::class,
            CreativesRepository::class
        );
        $this->app->bind(
            OrderProductsRepositoryInterface::class,
            OrderProductsRepository::class
        );
        $this->app->bind(
            VendorRepositoryInterface::class,
            VendorRepository::class
        );
        */
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

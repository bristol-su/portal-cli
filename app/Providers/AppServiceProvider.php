<?php

namespace App\Providers;

use App\Core\Contracts\Instance\Installer as InstallerContract;
use App\Core\Contracts\Instance\InstanceManager as InstanceManagerContract;
use App\Core\Contracts\Instance\MetaInstanceRepository as InstanceRepositoryContract;
use App\Core\Instance\Installer;
use App\Core\Instance\InstanceManager;
use App\Core\Instance\MetaInstanceRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(InstanceRepositoryContract::class, MetaInstanceRepository::class);
        $this->app->bind(InstanceManagerContract::class, InstanceManager::class);
        $this->app->bind(InstallerContract::class, Installer::class);
    }
}

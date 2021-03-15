<?php

namespace App\Providers;

use App\Core\Contracts\Helpers\Port\PortChecker;
use App\Core\Contracts\Helpers\Terminal\Executor;
use App\Core\Contracts\Instance\InstanceFactory as InstanceFactoryContract;
use App\Core\Contracts\Instance\InstanceRepository as InstanceManagerContract;
use App\Core\Contracts\Instance\MetaInstanceRepository as InstanceRepositoryContract;
use App\Core\Contracts\Helpers\Settings\SettingRepository as SettingRepositoryContract;
use App\Core\Helpers\Port\FSockOpenPortChecker;
use App\Core\Helpers\Terminal\ShellExecutor;
use App\Core\Install\CMSInstaller;
use App\Core\Install\FrontendInstaller;
use App\Core\Install\InstallManager;
use App\Core\Instance\InstanceFactory;
use App\Core\Instance\InstanceRepository;
use App\Core\Instance\MetaInstanceRepository;
use App\Core\Helpers\Settings\SettingRepository;
use App\Core\Helpers\Settings\Settings;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem as Flysystem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Repository $config)
    {
        app(InstallManager::class)->extend('cms', function(Container $container) {
            return $container->make(CMSInstaller::class);
        });
        app(InstallManager::class)->extend('frontend', function(Container $container) {
            return $container->make(FrontendInstaller::class);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(InstanceRepositoryContract::class, MetaInstanceRepository::class);
        $this->app->bind(InstanceManagerContract::class, InstanceRepository::class);
        $this->app->bind(SettingRepositoryContract::class, SettingRepository::class);
        $this->app->bind(PortChecker::class, FSockOpenPortChecker::class);
        $this->app->bind(Executor::class, ShellExecutor::class);

        $this->app->singleton(InstallManager::class);
        $this->app->bind(InstanceFactoryContract::class, InstanceFactory::class);

//        $this->app->singleton(InstanceResolverContract::class, InstanceResolver::class);
    }
}

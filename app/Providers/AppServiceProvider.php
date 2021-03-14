<?php

namespace App\Providers;

use App\Core\Contracts\Helpers\Port\PortChecker;
use App\Core\Contracts\Helpers\Terminal\Executor;
use App\Core\Contracts\Instance\Install\Installer as InstallerContract;
use App\Core\Contracts\Instance\InstanceRepository as InstanceManagerContract;
use App\Core\Contracts\Instance\MetaInstanceRepository as InstanceRepositoryContract;
use App\Core\Contracts\Helpers\Settings\SettingRepository as SettingRepositoryContract;
use App\Core\Helpers\Port\FSockOpenPortChecker;
use App\Core\Helpers\Terminal\ShellExecutor;
use App\Core\Instance\Install\CMSInstaller;
use App\Core\Instance\InstanceRepository;
use App\Core\Instance\MetaInstanceRepository;
use App\Core\Helpers\Settings\SettingRepository;
use App\Core\Helpers\Settings\Settings;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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

        $this->app['filesystem']->extend('atlas', function($app, $config) {
            $config['root'] = Settings::get('project-directory', '/tmp');

            return new Flysystem(
                new LocalAdapter(
                    $config['root'], LOCK_EX, LocalAdapter::DISALLOW_LINKS, []
                ),
                $config
            );
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
        $this->app->bind(InstallerContract::class, CMSInstaller::class);
        $this->app->bind(SettingRepositoryContract::class, SettingRepository::class);
        $this->app->bind(PortChecker::class, FSockOpenPortChecker::class);
        $this->app->bind(Executor::class, ShellExecutor::class);
    }
}

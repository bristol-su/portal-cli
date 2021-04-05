<?php

namespace App;

use App\Core\Contracts\Feature\FeatureRepository as FeatureRepositoryContract;
use App\Core\Contracts\Feature\FeatureResolver;
use App\Core\Contracts\Helpers\Composer\OperationManager as OperationManagerContract;
use App\Core\Contracts\Helpers\Port\PortChecker;
use App\Core\Contracts\Helpers\Terminal\Executor;
use App\Core\Contracts\Instance\InstanceRepository as InstanceManagerContract;
use App\Core\Contracts\Site\SiteRepository as SiteRepositoryContract;
use App\Core\Contracts\Helpers\Settings\SettingRepository as SettingRepositoryContract;
use App\Core\Contracts\Site\SiteResolver;
use App\Core\Feature\FeatureRepository;
use App\Core\Feature\SiteFeatureResolver;
use App\Core\Helpers\Composer\Operations\StandardOperationManager;
use App\Core\Helpers\Port\FSockOpenPortChecker;
use App\Core\Helpers\Terminal\ShellExecutor;
use App\Core\Pipeline\PipelineManager;
use App\Core\Instance\InstanceRepository;
use App\Core\Helpers\Settings\SettingRepository;
use App\Core\Site\FeatureSiteResolver;
use App\Core\Site\SettingsSiteResolver;
use App\Core\Site\SiteRepository;
use App\Core\Stubs\Entities\Stub;
use App\Core\Stubs\Entities\StubFile;
use App\Core\Stubs\Entities\StubReplacement;
use App\Core\Stubs\StubStore;
use App\Pipelines\CMSInstaller;
use App\Pipelines\FrontendInstaller;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Repository $config)
    {
        app(PipelineManager::class)->extend('cms', function(Container $container) {
            return $container->make(CMSInstaller::class);
        });
        app(PipelineManager::class)->extend('frontend', function(Container $container) {
            return $container->make(FrontendInstaller::class);
        });

        app(StubStore::class)->registerStub(
            (new Stub())->setName('routes')
                ->setDescription('A routes file for a demo')
                ->setDefaultLocation('routes')
                ->setStubFiles([
                    (new StubFile())->setStubPath(__DIR__ . '/../stubs/test/routes.api.php.stub')->setFileName('api.php')
                        ->setReplacements([
                            (new StubReplacement())->setType('bool')->setVariableName('extraRoute')
                                ->setDefault(true)
                                ->setQuestion('Would you like to include the extra route?'),
                            (new StubReplacement())->setType('string')->setVariableName('extraRouteText')
                                ->setDefault('Some default text')
                                ->setQuestion('What should the extra route return?')
                        ]),
                    (new StubFile())->setStubPath(__DIR__ . '/../stubs/test/routes.web.php.stub')->setFileName('web.php')
                ])
        );

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(ValidationServiceProvider::class);
        $this->app->bind(SiteRepositoryContract::class, SiteRepository::class);
        $this->app->bind(InstanceManagerContract::class, InstanceRepository::class);
        $this->app->bind(SettingRepositoryContract::class, SettingRepository::class);
        $this->app->bind(PortChecker::class, FSockOpenPortChecker::class);
        $this->app->bind(Executor::class, ShellExecutor::class);

        $this->app->singleton(PipelineManager::class);
        $this->app->singleton(StubStore::class);

        $this->app->bind(OperationManagerContract::class, StandardOperationManager::class);
        $this->app->bind(FeatureRepositoryContract::class, FeatureRepository::class);

        $this->app->bind(FeatureResolver::class, SiteFeatureResolver::class);
        $this->app->bind(SiteResolver::class, SettingsSiteResolver::class);
    }
}

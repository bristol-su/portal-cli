<?php

namespace Atlas;

use Atlas\Sites\AtlasCMS;
use Atlas\Sites\AtlasFrontend;
use Atlas\Sites\AtlasLicences;
use OriginEngine\Contracts\Feature\FeatureRepository as FeatureRepositoryContract;
use OriginEngine\Contracts\Feature\FeatureResolver;
use OriginEngine\Contracts\Helpers\Composer\OperationManager as OperationManagerContract;
use OriginEngine\Contracts\Helpers\Port\PortChecker;
use OriginEngine\Contracts\Helpers\Terminal\Executor;
use OriginEngine\Contracts\Instance\InstanceRepository as InstanceManagerContract;
use OriginEngine\Contracts\Site\SiteBlueprintStore;
use OriginEngine\Contracts\Site\SiteRepository as SiteRepositoryContract;
use OriginEngine\Contracts\Helpers\Settings\SettingRepository as SettingRepositoryContract;
use OriginEngine\Contracts\Site\SiteResolver;
use OriginEngine\Feature\FeatureRepository;
use OriginEngine\Feature\SiteFeatureResolver;
use OriginEngine\Helpers\Composer\Operations\StandardOperationManager;
use OriginEngine\Helpers\IO\IO;
use OriginEngine\Helpers\Port\FSockOpenPortChecker;
use OriginEngine\Helpers\Terminal\ShellExecutor;
use OriginEngine\OriginEngineServiceProvider;
use OriginEngine\Pipeline\PipelineManager;
use OriginEngine\Instance\InstanceRepository;
use OriginEngine\Helpers\Settings\SettingRepository;
use OriginEngine\Site\SettingsSiteResolver;
use OriginEngine\Site\SiteRepository;
use OriginEngine\Stubs\Stubs;
use OriginEngine\Stubs\StubStore;
use src\Pipelines\CMSInstaller;
use src\Pipelines\FrontendInstaller;
use src\Pipelines\LicenceInstaller;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class AtlasServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Repository $config, Stubs $stubs)
    {
//        app(PipelineManager::class)->extend('cms', function(Container $container) {
//            return $container->make(CMSInstaller::class);
//        });
//        app(PipelineManager::class)->extend('frontend', function(Container $container) {
//            return $container->make(FrontendInstaller::class);
//        });
//        app(PipelineManager::class)->extend('licensing', function(Container $container) {
//            return $container->make(LicenceInstaller::class);
//        });

        app(SiteBlueprintStore::class)->register('cms', new AtlasCMS());
        app(SiteBlueprintStore::class)->register('frontend', new AtlasFrontend());
        app(SiteBlueprintStore::class)->register('licence', new AtlasLicences());

        $stubs->newStub('routes', 'A routes file for a demo', 'routes')
            ->addFile(
                $stubs->newStubFile(
                    __DIR__ . '/../stubs/test/routes.api.php.stub', 'api.php'
                )
                    ->addReplacement(
                        $stubs->newSectionReplacement('extraRoute', 'Would you like an extra route?', true, null, [
                            $stubs->newStringReplacement('extraRouteText', 'What should the route return', 'Testing')
                        ])
                    )
            )->addFile(
                $stubs->newStubFile(
                    __DIR__ . '/../stubs/test/routes.web.php.stub', 'web.php'
                )
                    ->addReplacement($stubs->newStringReplacement('path', 'What is the route?', 'default-route'))
                    ->addReplacement(
                        $stubs->newArrayReplacement('models', 'What is the name of the models?', [], null,
                            $stubs->newStringReplacement('model', 'What is the model name?', 'Model'))
                    )
            )->addFile(
                $stubs->newStubFile(
                    __DIR__ . '/../stubs/test/web.php.backup.stub', fn($data) => sprintf('%s.php', $data['routesFileName']), 'secondary',
                    fn($data) => IO::confirm('Would you like to publish the optional routes file?')
                )
                    ->addReplacement($stubs->newStringReplacement('routesFileName', 'Name of the routes file?', 'route-file-name'))
                    ->addReplacement($stubs->newBooleanReplacement('includePost', 'Should we include a post request?', false))
                    ->addReplacement($stubs->newArrayReplacement('dbColumns', 'Define the cols', [], null,
                        $stubs->newTableColumnReplacement('dbColumns', 'What columns do you want for your xyz?', [])))
            );

        // Aim for the API

//        $stubs->newStub('routes', 'A routes file for a demo', 'routes')
//            ->addFile(__DIR__ . '/../stubs/test/routes.api.php.stub', 'api.php')
//            ->addFile(__DIR__ . '/../stubs/test/routes.web.php.stub', 'web.php')
//            ->addFile(__DIR__ . '/../stubs/test/web.php.backup.stub', fn($data) => sprintf('%s.php', $data['routesFileName']), 'secondary',
//                fn($data) => IO::confirm('Would you like to publish the optional routes file?')
//            )
//            ->addReplacement(
//                $stubs->newSectionReplacement('extraRoute', 'Would you like an extra route?', true, null, [
//                    $stubs->newStringReplacement('extraRouteText', 'What should the route return', 'Testing')
//                ])
//            )
//            ->addReplacement($stubs->newStringReplacement('path', 'What is the route?', 'default-route'))
//            ->addReplacement(
//                $stubs->newArrayReplacement('models', 'What is the name of the models?', [], null,
//                    $stubs->newStringReplacement('model', 'What is the model name?', 'Model'))
//            )
//            ->addReplacement($stubs->newStringReplacement('routesFileName', 'Name of the routes file?', 'route-file-name'))
//            ->addReplacement($stubs->newBooleanReplacement('includePost', 'Should we include a post request?', false))
//            ->addReplacement($stubs->newArrayReplacement('dbColumns', 'Define the cols', [], null,
//                $stubs->newTableColumnReplacement('dbColumns', 'What columns do you want for your xyz?', []))
//            );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(OriginEngineServiceProvider::class);
    }
}

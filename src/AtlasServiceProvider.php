<?php

namespace Atlas;

use Atlas\Sites\AtlasCMS\AtlasCMS;
use Atlas\Sites\AtlasFrontend\AtlasFrontend;
use Atlas\Sites\Licensing\Licensing;
use Illuminate\Support\Str;
use OriginEngine\Contracts\Site\SiteBlueprintStore;
use OriginEngine\Foundation\CliServiceProvider;
use OriginEngine\Helpers\Directory\Directory;
use OriginEngine\Helpers\IO\IO;
use OriginEngine\Helpers\Settings\SettingRepository;
use OriginEngine\Helpers\Storage\Filesystem;
use OriginEngine\Helpers\Terminal\Executor;
use OriginEngine\OriginEngineServiceProvider;
use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Pipeline\PipelineConfig;
use OriginEngine\Pipeline\PipelineHistory;
use OriginEngine\Pipeline\PipelineModifier;
use OriginEngine\Pipeline\Tasks\Origin\SetSetting;
use OriginEngine\Pipeline\Tasks\Utils\CreateAndRunTask;
use OriginEngine\Plugins\Dependencies\DependencyPlugin;
use OriginEngine\Plugins\HealthCheck\HealthCheckPlugin;
use OriginEngine\Plugins\Stubs\StubPlugin;
use OriginEngine\Plugins\Stubs\Stubs;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider;

class AtlasServiceProvider extends CliServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Repository $config, Stubs $stubs)
    {

        $this->registerPlugin(StubPlugin::class);
        $this->registerPlugin(DependencyPlugin::class);
        $this->registerPlugin(HealthCheckPlugin::class);

        app(SiteBlueprintStore::class)->register('cms', new AtlasCMS());
        app(SiteBlueprintStore::class)->register('frontend', new AtlasFrontend());
        app(SiteBlueprintStore::class)->register('licence', new Licensing());

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

        $pipelineModifier = app(PipelineModifier::class);

        $pipelineModifier->extend('post-update', function(Pipeline $pipeline) {
            $pipeline->runTaskAfter('set-project-directory', 'save-npm-token', new SetSetting('github-npm-token', ''));

            $pipeline->before('save-npm-token', function(PipelineConfig $config, PipelineHistory $history) {
                if(!app(SettingRepository::class)->has('github-npm-token')) {
                    $authToken = IO::ask(
                        'Provide a github personal access token with the read:packages scope',
                        null,
                        fn($token) => $token && is_string($token) && strlen($token) > 5
                    );
                    $config->add('save-npm-token', 'value', $authToken);
                } else {
                    return false;
                }
            });
        });

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

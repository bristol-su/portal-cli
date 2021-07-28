<?php

namespace Portal;

use OriginEngine\Helpers\IO\IO;
use OriginEngine\Helpers\Settings\SettingRepository;
use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Pipeline\PipelineConfig;
use OriginEngine\Pipeline\PipelineHistory;
use OriginEngine\Pipeline\PipelineModifier;
use OriginEngine\Pipeline\Tasks\Origin\SetSetting;
use Portal\Sites\Portal\Portal;
use OriginEngine\Contracts\Site\SiteBlueprintStore;
use OriginEngine\Foundation\CliServiceProvider;
use OriginEngine\OriginEngineServiceProvider;
use OriginEngine\Plugins\Dependencies\DependencyPlugin;
use OriginEngine\Plugins\HealthCheck\HealthCheckPlugin;
use OriginEngine\Plugins\Stubs\StubPlugin;

class PortalServiceProvider extends CliServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->registerPlugin(StubPlugin::class);
        $this->registerPlugin(DependencyPlugin::class);
        $this->registerPlugin(HealthCheckPlugin::class);

        app(SiteBlueprintStore::class)->register('portal', new Portal());

        app(PipelineModifier::class)->extend('post-update', function(Pipeline $pipeline) {
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

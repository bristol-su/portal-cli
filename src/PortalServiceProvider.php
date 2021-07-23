<?php

namespace Portal;

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

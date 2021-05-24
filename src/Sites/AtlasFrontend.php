<?php

namespace Atlas\Sites;

use OriginEngine\Helpers\Env\EnvRepository;
use OriginEngine\Helpers\LaravelSail\Sail;
use OriginEngine\Helpers\Storage\Filesystem;
use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Site\Site;
use OriginEngine\Site\SiteBlueprint;

class AtlasFrontend extends SiteBlueprint
{

    public function name(): string
    {
        return 'Atlas Frontend';
    }

    public function getUrls(Site $site): array
    {
        $envRepository = new EnvRepository($site->getDirectory());
        $env = $envRepository->get('.env.local');

        return [
            'Site' => sprintf('%s:%s', $env->getVariable('APP_URL'), $env->getVariable('APP_PORT'))
        ];
    }

    public function getStatus(Site $site): string
    {
        if(!Filesystem::create()->exists($site->getDirectory()->path())) {
            return Site::STATUS_MISSING;
        }

        if(Sail::isRunning($site)) {
            return Site::STATUS_READY;
        }

        return Site::STATUS_DOWN;
    }

    public function getInstallationPipeline(): Pipeline
    {
        return new Install();
    }

    public function getUninstallationPipeline(): Pipeline
    {
        // TODO: Implement getUninstallationPipeline() method.
    }

    public function getSiteUpPipeline(): Pipeline
    {
        // TODO: Implement getSiteUpPipeline() method.
    }

    public function getSiteDownPipeline(): Pipeline
    {
        // TODO: Implement getSiteDownPipeline() method.
    }
}

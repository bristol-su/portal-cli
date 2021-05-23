<?php

namespace Atlas\Sites;

use OriginEngine\Helpers\Env\EnvRepository;
use OriginEngine\Helpers\LaravelSail\Sail;
use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Site\Site;
use OriginEngine\Site\SiteBlueprint;

class AtlasFrontend extends SiteBlueprint
{

    public function name(): string
    {
        return 'Atlas Frontend';
    }

    public function getUrl(Site $site): string
    {
        $envRepository = new EnvRepository($site->getWorkingDirectory());
        $env = $envRepository->get('.env.local');

        $url = $env->getVariable('APP_URL');
        $port = $env->getVariable('APP_PORT');

        return sprintf('%s:%s', $url, $port);
    }

    public function getStatus(Site $site): string
    {
        if(!app(\OriginEngine\Contracts\Instance\InstanceRepository::class)->exists($site->getInstanceId())) {
            return Site::STATUS_MISSING;
        }

        if(Sail::isRunning($site)) {
            return Site::STATUS_READY;
        }

        return Site::STATUS_DOWN;
    }

    public function getInstallationPipeline(): Pipeline
    {
        return new AtlasInstallPipeline();
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

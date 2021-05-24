<?php

namespace Atlas\Sites\AtlasCMS;

use OriginEngine\Helpers\Env\EnvRepository;
use OriginEngine\Helpers\LaravelSail\Sail;
use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Site\Site;
use OriginEngine\Site\SiteBlueprint;

class AtlasCMS extends SiteBlueprint
{

    public function name(): string
    {
        return 'Atlas CMS';
    }

    public function getUrl(Site $site): string
    {
        $envRepository = new EnvRepository($site->getWorkingDirectory());
        $env = $envRepository->get('.env');

        $url = $env->getVariable('APP_URL');
        $port = $env->getVariable('APP_PORT');

        return sprintf('%s:%s', $url, $port);
    }

    public function getStatus(Site $site): string
    {
        if(!app(\OriginEngine\Contracts\Helpers\Directory\DirectoryValidator::class)->isValid($site->getDirectory())) {
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
        return new Uninstall();
    }

    public function getSiteUpPipeline(): Pipeline
    {
        return new Up();
    }

    public function getSiteDownPipeline(): Pipeline
    {
        return new Down();
    }
}

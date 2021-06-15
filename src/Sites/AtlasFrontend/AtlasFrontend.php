<?php

namespace Atlas\Sites\AtlasFrontend;

use OriginEngine\Helpers\Env\EnvRepository;
use OriginEngine\Helpers\LaravelSail\Sail;
use OriginEngine\Helpers\Storage\Filesystem;
use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Site\Site;
use OriginEngine\Site\SiteBlueprint;

class AtlasFrontend extends SiteBlueprint
{

    protected string $defaultBranch = 'develop';

    public function name(): string
    {
        return 'Atlas Frontend';
    }

    public function getUrls(Site $site): array
    {
        $envRepository = new EnvRepository($site->getDirectory());
        $env = $envRepository->get('.env');

        return [
            'Site' => sprintf('%s:%s', $env->getVariable('APP_URL'), $env->getVariable('APP_PORT')),
            'Emails' => sprintf('%s:%s', $env->getVariable('APP_URL'), $env->getVariable('FORWARD_MAILHOG_DASHBOARD_PORT'))
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

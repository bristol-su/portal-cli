<?php

namespace Atlas\Sites\AtlasCMSValet;

use OriginEngine\Helpers\Env\EnvRepository;
use OriginEngine\Helpers\LaravelSail\Sail;
use OriginEngine\Helpers\Storage\Filesystem;
use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Site\Site;
use OriginEngine\Site\SiteBlueprint;

class AtlasCMSValet extends SiteBlueprint
{

    protected string $defaultBranch = 'develop';

    public function name(): string
    {
        return 'Atlas CMS (Valet)';
    }

    public function getUrls(Site $site): array
    {
        $envRepository = new EnvRepository($site->getDirectory());
        $env = $envRepository->get('.env');

        return [
            'Site' => sprintf('%s:%s', $env->getVariable('APP_URL'), $env->getVariable('APP_PORT'))
        ];
    }

    public function getStatus(Site $site): string
    {
        if(!Filesystem::create()->exists($site->getDirectory()->path())) {
            return Site::STATUS_MISSING;
        }

        // This should check if there is a vendor folder
        if(Filesystem::create()->exists(sprintf('%s/%s', $site->getDirectory()->path(), 'vendor'))) {
            return Site::STATUS_READY;
        }

        return Site::STATUS_DOWN;
    }

    public function getInstallationPipeline(): Pipeline
    {
        return new Install();
    }

    public function getSiteUpPipeline(): Pipeline
    {
        return new Up();
    }

    public function getSiteDownPipeline(): Pipeline
    {
        return new Down();
    }

    public function getUninstallationPipeline(): Pipeline
    {
        return new Uninstall();
    }
}

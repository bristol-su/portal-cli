<?php

namespace src\Pipelines;

use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Pipeline\Tasks\InstallYarnDependencies;
use OriginEngine\Pipeline\Tasks\RunYarnScript;
use OriginEngine\Pipeline\Tasks\WaitForDocker;

class LicenceInstaller extends Pipeline
{

    protected function getTasks(): array
    {
        return [
            \OriginEngine\Pipeline\Tasks\CloneGitRepository::provision('git@github.com:ElbowSpaceUK/licensing', 'develop')
                ->withName('Downloading'),

            \OriginEngine\Pipeline\Tasks\InstallComposerDependencies::provision()->withName('Installing composer dependencies'),

            \OriginEngine\Pipeline\Tasks\CopyEnvironmentFile::provision('.env.example', '.env')
                ->withName('Set up local environment file'),

            \OriginEngine\Pipeline\Tasks\ValidatePortEntries::provision(
                '.env',
                ['APP_PORT', 'FORWARD_DB_PORT', 'FORWARD_MAILHOG_PORT', 'FORWARD_MAILHOG_DASHBOARD_PORT', 'FORWARD_REDIS_PORT', 'FORWARD_MEILISEARCH_PORT', 'FORWARD_SELENIUM_PORT'],
                ['HTTP', 'database', 'mail', 'mail dashboard', 'redis', 'meilisearch', 'selenium'],
                false)
                ->withName('Verifying port assignments'),

            \OriginEngine\Pipeline\Tasks\BringEnvironmentUp::provision(true),

            WaitForDocker::provision()
                ->withName('Waiting for Docker'),

            \OriginEngine\Pipeline\Tasks\GenerateAtlaslicationKey::provision('local')
                ->withName('Create local application key'),

            \OriginEngine\Pipeline\Tasks\MigrateDatabase::provision('local')
                ->withName('Migrate the local database'),
        ];
    }
}

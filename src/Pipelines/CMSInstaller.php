<?php

namespace src\Pipelines;

use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Pipeline\Tasks\InstallYarnDependencies;
use OriginEngine\Pipeline\Tasks\RunYarnScript;
use OriginEngine\Pipeline\Tasks\WaitForDocker;

class CMSInstaller extends Pipeline
{

    protected function getTasks(): array
    {
        return [
            \OriginEngine\Pipeline\Tasks\CloneGitRepository::provision('git@github.com:ElbowSpaceUK/AtlasCMS-Laravel-Template', 'remove-module-installer')
                ->withName('Downloading the CMS'),

            \OriginEngine\Pipeline\Tasks\InstallComposerDependencies::provision()->withName('Installing composer dependencies'),

            \OriginEngine\Pipeline\Tasks\EditEnvironmentFile::provision('.env.sail.example', '.env')
                ->withName('Set up local environment file'),
            \OriginEngine\Pipeline\Tasks\CheckPortsAreFree::provision(
                '.env',
                ['APP_PORT', 'FORWARD_DB_PORT', 'FORWARD_MAILHOG_PORT', 'FORWARD_MAILHOG_DASHBOARD_PORT', 'FORWARD_REDIS_PORT', 'FORWARD_SELENIUM_PORT', 'FORWARD_DB_TESTING_PORT'],
                ['HTTP', 'database', 'mail', 'mail dashboard', 'redis', 'selenium', 'test database'],
                false)
                ->withName('Verifying port assignments'),

            \OriginEngine\Pipeline\Tasks\EditEnvironmentFile::provision('.env', '.env.testing', [
                'APP_ENV' => 'testing', 'DB_CONNECTION' => 'mysql_testing'
            ])->withName('Set up testing environment file'),

            \OriginEngine\Pipeline\Tasks\EditEnvironmentFile::provision('.env', '.env.dusk.local', [
                'APP_ENV' => 'testing', 'DB_CONNECTION' => 'mysql_testing'
            ])->withName('Set up dusk environment file'),

            \OriginEngine\Pipeline\Tasks\BringEnvironmentUp::provision(true),

            WaitForDocker::provision()
                ->withName('Waiting for Docker'),

            InstallYarnDependencies::provision('/var/www/html/vendor/elbowspaceuk/core-module'),

//            RunYarnScript::provision('dev', '/var/www/html/vendor/elbowspaceuk/core-module')
//                ->withName('Compile frontend assets'),

            \OriginEngine\Pipeline\Tasks\GenerateAtlaslicationKey::provision('local')
                ->withName('Create local application key'),

            \OriginEngine\Pipeline\Tasks\GenerateAtlaslicationKey::provision('testing')
                ->withName('Create testing application key'),

            \OriginEngine\Pipeline\Tasks\GenerateAtlaslicationKey::provision('dusk.local')
                ->withName('Create dusk application key'),

            \OriginEngine\Pipeline\Tasks\MigrateDatabase::provision('local')
                ->withName('Migrate the local database'),

            \OriginEngine\Pipeline\Tasks\MigrateDatabase::provision('testing')
                ->withName('Migrate the testing database'),

            \OriginEngine\Pipeline\Tasks\SeedLaravelModule::provision('Core', 'CoreDatabaseSeeder', 'local')
                ->withName('Seed the local database')
        ];
    }
}

<?php

namespace Atlas\Sites\AtlasCMS;

use OriginEngine\Pipeline\Tasks\Git\CloneGitRepository;
use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Pipeline\Tasks\Files\CopyFile;
use OriginEngine\Pipeline\Tasks\EditEnvironmentFile;
use OriginEngine\Pipeline\Tasks\LaravelSail\GenerateApplicationKey;
use OriginEngine\Pipeline\Tasks\LaravelSail\InstallYarnDependencies;
use OriginEngine\Pipeline\Tasks\LaravelSail\MigrateDatabase;
use OriginEngine\Pipeline\Tasks\LaravelSail\RunYarnScript;
use OriginEngine\Pipeline\Tasks\WaitForDocker;

class Install extends Pipeline
{

    public function __construct()
    {
//        $this->before('closure', function(PipelineConfig $config, PipelineHistory $history) {
//            $config->add('closure', 'test', 'two');
//        });
//        $this->before('edit-testing-env-file', function(PipelineConfig $config, PipelineHistory $history) {
//            $config->add('closure', 'test', 'two');
//        });
    }

    public function getTasks(): array
    {
        return [
            'clone' => (new CloneGitRepository('git@github.com:ElbowSpaceUK/AtlasCMS-Laravel-Template', 'remove-module-installer')),
            'composer-install' => new \OriginEngine\Pipeline\Tasks\LaravelSail\InstallComposerDependencies(),
            'create-local-env-file' => new CopyFile('.env.sail.example', '.env'),
            'check-ports-free' => new \OriginEngine\Pipeline\Tasks\CheckPortsAreFree(
                '.env',
                [
                    'APP_PORT' => 'HTTP',
                    'FORWARD_DB_PORT' => 'database',
                    'FORWARD_MAILHOG_PORT' => 'mail',
                    'FORWARD_MAILHOG_DASHBOARD_PORT' => 'mail dashboard',
                    'FORWARD_REDIS_PORT' => 'redis',
                    'FORWARD_SELENIUM_PORT' => 'selenium',
                    'FORWARD_DB_TESTING_PORT' => 'test database'
                ],
                false),

            'create-testing-env-file' => new CopyFile('.env', '.env.testing'),

            'override-testing-environment' => new EditEnvironmentFile([
                'APP_ENV' => 'testing',
                'DB_CONNECTION' => 'mysql_testing'
            ], '.env.testing'),

            'bring-sail-up' => new \OriginEngine\Pipeline\Tasks\LaravelSail\BringSailEnvironmentUp(),

            'wait-for-docker' => new WaitForDocker(),

            'install-yarn-dependencies' => new InstallYarnDependencies('/var/www/html/vendor/elbowspaceuk/core-module'),

//            'run-yarn-script' => new RunYarnScript('dev', '/var/www/html/vendor/elbowspaceuk/core-module'),

            'create-application-key' => new GenerateApplicationKey('local', '.env'),

            'create-testing-application-key' => new GenerateApplicationKey('testing', '.env.testing'),

            'migrate-main-db' => new MigrateDatabase('local'),

            'migrate-testing-db' => new MigrateDatabase('testing'),

            'seed-core-module' => new \OriginEngine\Pipeline\Tasks\LaravelSail\SeedLaravelModule('Core', 'CoreDatabaseSeeder', 'local')
        ];
    }

    public function aliasedConfig(): array
    {
        return [];
    }
}

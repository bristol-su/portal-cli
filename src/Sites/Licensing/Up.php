<?php

namespace Atlas\Sites\Licensing;

use Illuminate\Support\Collection;
use OriginEngine\Helpers\Terminal\Executor;
use OriginEngine\Pipeline\PipelineConfig;
use OriginEngine\Pipeline\PipelineHistory;
use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Pipeline\Tasks\Utils\Closure;
use OriginEngine\Pipeline\Tasks\Files\CopyFile;
use OriginEngine\Pipeline\Tasks\EditEnvironmentFile;
use OriginEngine\Pipeline\Tasks\LaravelSail\MigrateDatabase;
use OriginEngine\Pipeline\Tasks\WaitForDocker;

class Up extends Pipeline
{

    public function tasks(): array
    {
        return [
            'composer-install' => new \OriginEngine\Pipeline\Tasks\LaravelSail\InstallComposerDependencies(),

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

            'wait-for-docker' => (new WaitForDocker())->setUpName('Waiting for Docker. This may take a minute.'),

//            'run-yarn-script' => new RunYarnScript('dev', '/var/www/html/vendor/elbowspaceuk/core-module'),

            'migrate-main-db' => new MigrateDatabase('local'),

            'migrate-testing-db' => new MigrateDatabase('testing'),
        ];
    }

    public function aliasedConfig(): array
    {
        return [];
    }
}

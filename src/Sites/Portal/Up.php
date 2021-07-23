<?php

namespace Portal\Sites\Portal;

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
            'composer-install' => new \OriginEngine\Pipeline\Tasks\LaravelSail\InstallComposerDependencies('80'),

            'check-ports-free' => new \OriginEngine\Pipeline\Tasks\CheckPortsAreFree(
                '.env',
                [
                    'APP_PORT' => 'HTTP',
                    'FORWARD_DB_PORT' => 'database',
                    'FORWARD_MAILHOG_PORT' => 'mail',
                    'FORWARD_MAILHOG_DASHBOARD_PORT' => 'mail dashboard',
                    'FORWARD_REDIS_PORT' => 'redis'
                ],
                false),

            'bring-sail-up' => new \OriginEngine\Pipeline\Tasks\LaravelSail\BringSailEnvironmentUp(),

            'wait-for-docker' => (new WaitForDocker())->setUpName('Waiting for Docker. This may take a minute.'),

            'migrate-local-db' => new MigrateDatabase('local'),

        ];
    }

    public function aliasedConfig(): array
    {
        return [];
    }
}

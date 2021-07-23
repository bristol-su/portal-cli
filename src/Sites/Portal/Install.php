<?php

namespace Portal\Sites\Portal;

use Illuminate\Support\Collection;
use OriginEngine\Contracts\Helpers\Settings\SettingRepository;
use OriginEngine\Helpers\Directory\Directory;
use OriginEngine\Helpers\Env\EnvRepository;
use OriginEngine\Helpers\IO\IO;
use OriginEngine\Helpers\Terminal\Executor;
use OriginEngine\Pipeline\PipelineConfig;
use OriginEngine\Pipeline\PipelineHistory;
use OriginEngine\Pipeline\Tasks\Files\OverwriteFile;
use OriginEngine\Pipeline\Tasks\Git\CloneGitRepository;
use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Pipeline\Tasks\Files\CopyFile;
use OriginEngine\Pipeline\Tasks\EditEnvironmentFile;
use OriginEngine\Pipeline\Tasks\LaravelSail\GenerateApplicationKey;
use OriginEngine\Pipeline\Tasks\LaravelSail\InstallNpmDependencies;
use OriginEngine\Pipeline\Tasks\LaravelSail\MigrateDatabase;
use OriginEngine\Pipeline\Tasks\LaravelSail\RunNpmScript;
use OriginEngine\Pipeline\Tasks\LaravelSail\SeedLaravel;
use OriginEngine\Pipeline\Tasks\Utils\Closure;
use OriginEngine\Pipeline\Tasks\WaitForDocker;

class Install extends Pipeline
{

    public function tasks(): array
    {
        return [
            'clone' => (new CloneGitRepository('git@github.com:bristol-su/portal', 'portal-v4-ported')),
            'composer-install' => new \OriginEngine\Pipeline\Tasks\LaravelSail\InstallComposerDependencies('80'),
            'create-local-env-file' => new CopyFile('.env.sail.example', '.env'),
            'check-ports-free' => new \OriginEngine\Pipeline\Tasks\CheckPortsAreFree(
                '.env',
                [
                    'APP_PORT' => 'HTTP',
                    'FORWARD_DB_PORT' => 'database',
                    'FORWARD_MAILHOG_PORT' => 'mail',
                    'FORWARD_MAILHOG_DASHBOARD_PORT' => 'mail dashboard',
                    'FORWARD_REDIS_PORT' => 'redis',
                ],
                false),

            'bring-sail-up' => new \OriginEngine\Pipeline\Tasks\LaravelSail\BringSailEnvironmentUp(),

            'wait-for-docker' => new WaitForDocker(),

            'create-application-key' => new GenerateApplicationKey('local', '.env'),

            'migrate-db' => new MigrateDatabase('local'),

            'install-npm-dependencies' => new InstallNpmDependencies(),

            'compile-assets' => new RunNpmScript('dev')

        ];
    }

    public function aliasedConfig(): array
    {
        return [];
    }
}

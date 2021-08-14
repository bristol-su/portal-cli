<?php

namespace Portal\Sites\Playground;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use OriginEngine\Helpers\Directory\Directory;
use OriginEngine\Helpers\Env\EnvRepository;
use OriginEngine\Pipeline\PipelineConfig;
use OriginEngine\Pipeline\PipelineHistory;
use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Pipeline\Tasks\EditEnvironmentFile;
use OriginEngine\Pipeline\Tasks\LaravelSail\InstallNpmDependencies;
use OriginEngine\Pipeline\Tasks\LaravelSail\MigrateDatabase;
use OriginEngine\Pipeline\Tasks\LaravelSail\RunNpmScript;
use OriginEngine\Pipeline\Tasks\WaitForDocker;

class Up extends Pipeline
{

    public function __construct()
    {
        $this->before('set-urls', function(PipelineConfig $config, PipelineHistory $history, string $task, Directory $workingDirectory) {
            // Get the URLs and the ports
            $envRepo = new EnvRepository($workingDirectory);
            $env = $envRepo->get('.env');

            $appUrl = sprintf('http://%s:%s', $env->getVariable('APP_SERVICE'), $env->getVariable('APP_PORT'));
            $apiUrl = sprintf('http://%s:%s/api', $env->getVariable('APP_SERVICE'), $env->getVariable('APP_PORT'));
            $controlApiUrl = sprintf('http://%s:%s%s', $env->getVariable('APP_SERVICE'), $env->getVariable('APP_PORT'), $env->getVariable('CONTROL_API_PREFIX'));

            $config->add($task, 'replace', [
                'APP_URL' => $appUrl,
                'API_URL' => $apiUrl,
                'MIX_APP_URL' => $appUrl,
                'MIX_API_URL' => $apiUrl,
                'MIX_CONTROL_API_URL' => $controlApiUrl,
            ]);
        });
    }

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

            'set-urls' => new EditEnvironmentFile('.env', []),

            'bring-sail-up' => new \OriginEngine\Pipeline\Tasks\LaravelSail\BringSailEnvironmentUp(),

            'wait-for-docker' => (new WaitForDocker())->setUpName('Waiting for Docker. This may take a minute.'),

            'migrate-local-db' => new MigrateDatabase('local'),

            'install-npm-dependencies' => new InstallNpmDependencies(),

            'compile-assets' => new RunNpmScript('dev'),

        ];
    }

    public function aliasedConfig(): array
    {
        return [];
    }
}

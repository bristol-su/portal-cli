<?php

namespace Portal\Sites\Portal;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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
use Portal\Tasks\GeneratePassportApiKeys;

class Install extends Pipeline
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
            'clone' => (new CloneGitRepository('git@github.com:bristol-su/portal', 'develop')),
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

            'set-urls' => new EditEnvironmentFile('.env', []),

            'bring-sail-up' => new \OriginEngine\Pipeline\Tasks\LaravelSail\BringSailEnvironmentUp(),

            'wait-for-docker' => new WaitForDocker(),

            'create-application-key' => new GenerateApplicationKey('local', '.env'),

            'migrate-db' => new MigrateDatabase('local'),

            'create-npm-registration' => new OverwriteFile(
                '.npmrc',
                sprintf('//npm.pkg.github.com/:_authToken=%s', app(SettingRepository::class)->get('github-npm-token'))
                . PHP_EOL . '@bristol-su:registry=https://npm.pkg.github.com'
            ),

            'install-npm-dependencies' => new InstallNpmDependencies(),

            'compile-assets' => new RunNpmScript('dev'),

            'generate-api-keys' => new GeneratePassportApiKeys()

        ];
    }

    public function aliasedConfig(): array
    {
        return [];
    }
}

<?php

namespace Atlas\Sites\Licensing;

use Illuminate\Support\Collection;
use OriginEngine\Contracts\Helpers\Settings\SettingRepository;
use OriginEngine\Helpers\Directory\Directory;
use OriginEngine\Helpers\Env\EnvRepository;
use OriginEngine\Helpers\IO\IO;
use OriginEngine\Helpers\Terminal\Executor;
use OriginEngine\Pipeline\PipelineConfig;
use OriginEngine\Pipeline\PipelineHistory;
use OriginEngine\Pipeline\Tasks\Files\CreateEmptyFile;
use OriginEngine\Pipeline\Tasks\Files\OverwriteFile;
use OriginEngine\Pipeline\Tasks\Git\CloneGitRepository;
use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Pipeline\Tasks\Files\CopyFile;
use OriginEngine\Pipeline\Tasks\EditEnvironmentFile;
use OriginEngine\Pipeline\Tasks\LaravelSail\GenerateApplicationKey;
use OriginEngine\Pipeline\Tasks\LaravelSail\InstallYarnDependencies;
use OriginEngine\Pipeline\Tasks\LaravelSail\MigrateDatabase;
use OriginEngine\Pipeline\Tasks\LaravelSail\NewLaravelInstance;
use OriginEngine\Pipeline\Tasks\LaravelSail\RunYarnScript;
use OriginEngine\Pipeline\Tasks\LaravelSail\SeedLaravel;
use OriginEngine\Pipeline\Tasks\Utils\Closure;
use OriginEngine\Pipeline\Tasks\WaitForDocker;

class Install extends Pipeline
{

    public function __construct()
    {
        $this->before('proxy-github-through-ssh', function (PipelineConfig $config, PipelineHistory $history, string $key, Directory $directory) {
            $envRepo = new EnvRepository($directory);
            $env = $envRepo->get('.env');

            $config->add('proxy-github-through-ssh', 'app-service', $env->getVariable('APP_SERVICE', 'atlas.su.test'));
        });

        $this->before('add-github-token', function (PipelineConfig $config, PipelineHistory $history, string $key, Directory $directory) {
            $authToken = IO::ask(
                'Provide a github personal access token with at least the repo:status and public_repo scopes.',
                null,
                fn($token) => $token && is_string($token) && strlen($token) > 5
            );

            $config->add('add-github-token', 'replace', [
                'GITHUB_TOKEN' => $authToken
            ]);
        });

    }

    public function tasks(): array
    {
        $home = Executor::cd(Directory::fromFullPath('~'))->execute('pwd');
        $npmrc = $home . DIRECTORY_SEPARATOR . '.npmrc';

        return [
//            'new-instance' => new NewLaravelInstance(),
            'clone' => (new CloneGitRepository('git@github.com:ElbowSpaceUK/licensing', 'develop')),
            'composer-install' => new \OriginEngine\Pipeline\Tasks\LaravelSail\InstallComposerDependencies('80'),
            'create-local-env-file' => new CopyFile('.env.example', '.env'),
            'check-ports-free' => new \OriginEngine\Pipeline\Tasks\CheckPortsAreFree(
                '.env',
                [
                    'APP_PORT' => 'HTTP',
                    'FORWARD_DB_PORT' => 'database',
                    'FORWARD_MAILHOG_PORT' => 'mail',
                    'FORWARD_MAILHOG_DASHBOARD_PORT' => 'mail dashboard',
                    'FORWARD_REDIS_PORT' => 'redis',
                    'FORWARD_MEILISEARCH_PORT' => 'meilisearch',
                    'FORWARD_SELENIUM_PORT' => 'selenium'
                ],
                false),

            'bring-sail-up' => new \OriginEngine\Pipeline\Tasks\LaravelSail\BringSailEnvironmentUp(),

            'wait-for-docker' => new WaitForDocker(),

            'create-npm-registration' => new OverwriteFile(
                '.npmrc',
                sprintf('//npm.pkg.github.com/:_authToken=%s', app(SettingRepository::class)->get('github-npm-token'))
                . PHP_EOL . '@elbowspaceuk:registry=https://npm.pkg.github.com'
            ),

            'proxy-github-through-ssh' => new Closure(function (Directory $directory, Collection $config) {
                return Executor::cd($directory)->execute(
                    sprintf('./vendor/bin/sail exec -u sail %s bash -c \'git config --global url."git@github.com:".insteadOf "https://github.com/"\'', $config->get('app-service', 'atlas.su.test.local'))
                );
            }, function (Collection $config, Collection $output, Directory $directory) {
                Executor::cd($directory)->execute(
                    sprintf('./vendor/bin/sail exec -u sail %s bash -c \'git config --global --unset url."git@github.com:".insteadOf\'', $config->get('app-service', 'atlas.su.test.local'))
                );
            }),

            'create-application-key' => new GenerateApplicationKey('local', '.env'),

            'migrate-db' => new MigrateDatabase('local'),

            'seed-db' => new SeedLaravel('ElbowSpaceInitialSeeder', 'local'),

            'add-github-token' => new EditEnvironmentFile('.env', [])

        ];
    }

    public function aliasedConfig(): array
    {
        return [];
    }
}

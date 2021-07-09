<?php

namespace Atlas\Sites\AtlasCMS;

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
use OriginEngine\Pipeline\Tasks\Utils\Closure;
use OriginEngine\Pipeline\Tasks\WaitForDocker;

class Install extends Pipeline
{

    public function __construct()
    {
        $this->before('proxy-github-through-ssh', function (PipelineConfig $config, PipelineHistory $history, string $key, Directory $directory) {
            $env = (new EnvRepository($directory))->get('.env');

            $config->add('proxy-github-through-ssh', 'app-service', $env->getVariable('APP_SERVICE', 'atlas.su.test'));
        });
    }

    public function tasks(): array
    {
        $home = Executor::cd(Directory::fromFullPath('~'))->execute('pwd');
        $npmrc = $home . DIRECTORY_SEPARATOR . '.npmrc';

        return [
            'clone' => (new CloneGitRepository('git@github.com:ElbowSpaceUK/AtlasCMS-Laravel-Template', 'develop')),
            'composer-install' => new \OriginEngine\Pipeline\Tasks\LaravelSail\InstallComposerDependencies('74'),
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

            'override-testing-environment' => new EditEnvironmentFile('.env.testing', [
                'APP_ENV' => 'testing',
                'DB_CONNECTION' => 'mysql_testing'
            ]),

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

            'install-yarn-dependencies' => new InstallYarnDependencies('/var/www/html/vendor/elbowspaceuk/core-module'),

            'run-yarn-script' => new RunYarnScript('dev', '/var/www/html/vendor/elbowspaceuk/core-module'),

            'create-application-key' => new GenerateApplicationKey('local', '.env'),

            'create-testing-application-key' => new GenerateApplicationKey('testing', '.env.testing'),

            'migrate-local-db' => new MigrateDatabase('local'),

            'migrate-testing-db' => new MigrateDatabase('testing'),

            // 'seed-core-module' => new \OriginEngine\Pipeline\Tasks\LaravelSail\SeedLaravelModule('Core', 'CoreDatabaseSeeder', 'local')
        ];
    }

    public function aliasedConfig(): array
    {
        return [
            'repository' => 'clone.repository',
        ];
    }
}

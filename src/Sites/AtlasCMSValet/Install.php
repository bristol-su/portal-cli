<?php

namespace Atlas\Sites\AtlasCMSValet;

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
//        $this->before('proxy-github-through-ssh', function (PipelineConfig $config, PipelineHistory $history, string $key, Directory $directory) {
//            $env = (new EnvRepository($directory))->get('.env');
//
//            $config->add('proxy-github-through-ssh', 'app-service', $env->getVariable('APP_SERVICE', 'atlas.test'));
//        });
    }

    public function tasks(): array
    {
//        $home = Executor::cd(Directory::fromFullPath('~'))->execute('pwd');
//        $npmrc = $home . DIRECTORY_SEPARATOR . '.npmrc';

        return [
            'clone' => (new CloneGitRepository('git@github.com:ElbowSpaceUK/AtlasCMS-Laravel-Template', 'develop')),
            'composer-install' => new \OriginEngine\Pipeline\Tasks\LaravelSail\InstallComposerDependencies('74'),
            'create-local-env-file' => new CopyFile('.env.sail.example', '.env'),

            'override-environment-variables' => new EditEnvironmentFile('.env', [
                'APP_URL' => 'http://atlas.test',
                'DB_HOST' => '127.0.0.1',
                'DB_USERNAME' => 'root',
                'DB_PASSWORD' => '',
                'DB_TESTING_HOST' => '127.0.0.1',
                'DB_TESTING_USERNAME' => 'root',
                'DB_TESTING_PASSWORD' => '',
                'FORWARD_DB_PORT' => '3306'
            ]),

            'create-testing-env-file' => new CopyFile('.env', '.env.testing'),

            'override-testing-environment' => new EditEnvironmentFile('.env.testing', [
                'APP_ENV' => 'testing',
                'DB_CONNECTION' => 'mysql_testing',
                'MAIL_HOST' => '',
                'MAIL_MAILER' => 'log'
            ]),

            'create-npm-registration' => new OverwriteFile(
                '.npmrc',
                sprintf('//npm.pkg.github.com/:_authToken=%s', app(SettingRepository::class)->get('github-npm-token'))
                . PHP_EOL . '@elbowspaceuk:registry=https://npm.pkg.github.com'
            ),

            'install-yarn-dependencies' => new InstallYarnDependencies('./vendor/elbowspaceuk/core-module', true),

            'run-yarn-script' => new RunYarnScript('dev', './vendor/elbowspaceuk/core-module', true),

            'create-application-key' => new Closure(function (Directory $directory, Collection $config) {
                return Executor::cd($directory)->execute('php artisan key:generate');
            }),

            'create-testing-application-key' => new GenerateApplicationKey('testing', '.env.testing', true),

            'migrate-local-db' => new MigrateDatabase('local', true),

            'migrate-testing-db' => new MigrateDatabase('testing', true),

            'register-valet' => new Closure(function (Directory $directory, Collection $config) {
                return Executor::cd($directory)->execute('valet link');
            }),

            'generate-valet-ssl' => new Closure(function (Directory $directory, Collection $config) {
                return Executor::cd($directory)->execute('valet secure');
            }),

        ];
    }

    public function aliasedConfig(): array
    {
        return [
            'repository' => 'clone.repository',
        ];
    }
}

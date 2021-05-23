<?php

namespace Atlas\Sites;

use Illuminate\Support\Collection;
use OriginEngine\Helpers\IO\IO;
use OriginEngine\Helpers\WorkingDirectory\WorkingDirectory;
use OriginEngine\Pipeline\Tasks\CloneGitRepository;
use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Pipeline\Tasks\Closure;
use OriginEngine\Pipeline\Tasks\CopyFile;
use OriginEngine\Pipeline\Tasks\EditEnvironmentFile;
use OriginEngine\Pipeline\Tasks\InstallComposerDependencies;
use OriginEngine\Pipeline\Tasks\InstallYarnDependencies;
use OriginEngine\Pipeline\Tasks\WaitForDocker;

class AtlasInstallPipeline extends Pipeline
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
            'composer-install' => new InstallComposerDependencies(),
            'create-local-env-file' => new CopyFile('.env.sail.example', '.env'),
//            \OriginEngine\Pipeline\Tasks\ValidatePortEntries::provision(
//                '.env.local',
//                ['APP_PORT', 'FORWARD_DB_PORT', 'FORWARD_MAILHOG_PORT', 'FORWARD_MAILHOG_DASHBOARD_PORT', 'FORWARD_REDIS_PORT', 'FORWARD_SELENIUM_PORT', 'FORWARD_DB_TESTING_PORT'],
//                ['HTTP', 'database', 'mail', 'mail dashboard', 'redis', 'selenium', 'test database'],
//                false)
//                ->withName('Verifying port assignments'),
            'check-env-file' => new Closure(fn(Collection $config, WorkingDirectory $workingDirectory) => IO::info(file_get_contents($workingDirectory->path() . '.env'))),
            'edit-testing-env-file' => new EditEnvironmentFile([
                'APP_ENV' => 'testing', 'DB_CONNECTION' => 'mysql_testing'
            ], '.env'),
            'check-env-file-new' => new Closure(fn(Collection $config, WorkingDirectory $workingDirectory) => IO::info(file_get_contents($workingDirectory->path() . '.env'))),
            'throw-exception' => new Closure(function(Collection $config, WorkingDirectory $workingDirectory) {
                throw new \Exception('Test');
            }),


//
//            \OriginEngine\Pipeline\Tasks\EditEnvironmentFile::provision('.env.local', '.env.dusk.local', [
//                'APP_ENV' => 'testing', 'DB_CONNECTION' => 'mysql_testing'
//            ])->withName('Set up dusk environment file'),
//
//            \OriginEngine\Pipeline\Tasks\BringEnvironmentUp::provision(true),
//
//            WaitForDocker::provision()
//                ->withName('Waiting for Docker'),
//
//            InstallYarnDependencies::provision('/var/www/html/vendor/elbowspaceuk/core-module'),
//
////            RunYarnScript::provision('dev', '/var/www/html/vendor/elbowspaceuk/core-module')
////                ->withName('Compile frontend assets'),
//
//            \OriginEngine\Pipeline\Tasks\GenerateAtlaslicationKey::provision('local')
//                ->withName('Create local application key'),
//
//            \OriginEngine\Pipeline\Tasks\GenerateAtlaslicationKey::provision('testing')
//                ->withName('Create testing application key'),
//
//            \OriginEngine\Pipeline\Tasks\GenerateAtlaslicationKey::provision('dusk.local')
//                ->withName('Create dusk application key'),
//
//            \OriginEngine\Pipeline\Tasks\MigrateDatabase::provision('local')
//                ->withName('Migrate the local database'),
//
//            \OriginEngine\Pipeline\Tasks\MigrateDatabase::provision('testing')
//                ->withName('Migrate the testing database'),
//
//            \OriginEngine\Pipeline\Tasks\SeedLaravelModule::provision('Core', 'CoreDatabaseSeeder', 'local')
//                ->withName('Seed the local database')
        ];
    }

    public function aliasedConfig(): array
    {
        return [];
    }
}

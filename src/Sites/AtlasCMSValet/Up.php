<?php

namespace Atlas\Sites\AtlasCMSValet;

use Illuminate\Support\Collection;
use OriginEngine\Helpers\Terminal\Executor;
use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Pipeline\Tasks\LaravelSail\InstallYarnDependencies;
use OriginEngine\Pipeline\Tasks\LaravelSail\RunYarnScript;
use OriginEngine\Pipeline\Tasks\Utils\Closure;
use OriginEngine\Pipeline\Tasks\LaravelSail\MigrateDatabase;
use OriginEngine\Helpers\Directory\Directory;

class Up extends Pipeline
{

    public function tasks(): array
    {
        return [
            'composer-install' => new \OriginEngine\Pipeline\Tasks\LaravelSail\InstallComposerDependencies('74'),

            'install-yarn-dependencies' => new InstallYarnDependencies('./vendor/elbowspaceuk/core-module', true),

            'run-yarn-script' => new RunYarnScript('dev', './vendor/elbowspaceuk/core-module', true),

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
        return [];
    }
}

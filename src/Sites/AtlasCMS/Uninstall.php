<?php

namespace Atlas\Sites\AtlasCMS;

use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Pipeline\Tasks\DeleteFiles;
use OriginEngine\Pipeline\Tasks\LaravelSail\BringSailEnvironmentDown;

class Uninstall extends Pipeline
{

    public function tasks(): array
    {
        return [
            'bring-environment-down' => new BringSailEnvironmentDown(true),

            'remove-files' => new DeleteFiles()
        ];
    }

    public function aliasedConfig(): array
    {
        return [];
    }
}

<?php

namespace Portal\Sites\Playground;

use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Pipeline\Tasks\DeleteFiles;
use OriginEngine\Pipeline\Tasks\LaravelSail\BringSailEnvironmentDown;

class Down extends Pipeline
{

    public function tasks(): array
    {
        return [
            'bring-environment-down' => new BringSailEnvironmentDown(true),

            'remove-vendor-files' => new DeleteFiles('vendor'),

            'remove-node-files' => new DeleteFiles('node_modules')
        ];
    }

    public function aliasedConfig(): array
    {
        return [];
    }
}

<?php

namespace Atlas\Sites\AtlasCMS;

use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Pipeline\Tasks\DeleteFiles;
use OriginEngine\Pipeline\Tasks\LaravelSail\BringSailEnvironmentDown;

class Down extends Pipeline
{

    public function getTasks(): array
    {
        return [
            'bring-environment-down' => new BringSailEnvironmentDown(true),

            'remove-files' => new DeleteFiles('vendor')
        ];
    }

    public function aliasedConfig(): array
    {
        return [];
    }
}

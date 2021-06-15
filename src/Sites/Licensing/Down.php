<?php

namespace Atlas\Sites\Licensing;

use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Pipeline\Tasks\DeleteFiles;
use OriginEngine\Pipeline\Tasks\LaravelSail\BringSailEnvironmentDown;

class Down extends Pipeline
{

    public function tasks(): array
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

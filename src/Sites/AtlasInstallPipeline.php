<?php

namespace Atlas\Sites;

use OriginEngine\Helpers\WorkingDirectory\WorkingDirectory;
use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Pipeline\ProvisionedTask;
use OriginEngine\Pipeline\PipelineConfig;
use OriginEngine\Pipeline\Tasks\Closure;

class AtlasInstallPipeline extends Pipeline
{


    protected function getTasks(): array
    {
        return [
            new Closure(function(PipelineConfig $taskConfig) {

            })
        ]
    }
}

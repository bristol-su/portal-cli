<?php

namespace App\Pipelines;

use App\Core\Pipeline\Pipeline;
use App\Core\Pipeline\Tasks\NotReadyError;

class FrontendInstaller extends Pipeline
{

    protected function getTasks(): array
    {
        return [
            NotReadyError::class
        ];
    }
}

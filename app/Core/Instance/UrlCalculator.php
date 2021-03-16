<?php

namespace App\Core\Instance;

use App\Core\Helpers\Env\EnvRepository;
use App\Core\Helpers\Terminal\Executor;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;

class UrlCalculator
{

    public static function calculate(string $instanceId): string
    {
        $directory = WorkingDirectory::fromInstanceId($instanceId);

        $envRepository = new EnvRepository($directory);
        $env = $envRepository->get(EnvRepository::ROOT); // TODO Abstract

        $url = $env->getVariable('APP_URL');
        $port = $env->getVariable('APP_PORT');

        return sprintf('%s:%s', $url, $port);
    }

}

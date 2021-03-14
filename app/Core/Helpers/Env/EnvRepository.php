<?php

namespace App\Core\Helpers\Env;

use Illuminate\Support\Facades\Storage;

class EnvRepository
{

    const ROOT = '.env';

    const TESTING = '.env.testing';

    const DUSK = '.env.dusk.local';

    public function getForInstance(string $instanceId, string $type = null): Env
    {
        $envRetriever = new EnvRetriever(Storage::path($instanceId));
        $env = $envRetriever->get($type ?? static::ROOT);

        return EnvFactory::fromDotEnv($env);
    }

    public function updateForInstance(string $instanceId, Env $env, $type = null): void
    {
        $envFile = '';
        foreach($env->getVariables() as $name => $value) {
            $envFile .= sprintf('%s="%s"', $name, $value) . PHP_EOL;
        }
        Storage::put(
            sprintf('%s/%s', $instanceId, $type ?? static::ROOT),
            $envFile
        );
    }

}

<?php

namespace App\Core\Instance;

use App\Core\Helpers\Terminal\Executor;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;

class StatusCalculator
{

    public static function calculate(string $instanceId)
    {
        if(!app(\App\Core\Contracts\Instance\InstanceRepository::class)->exists($instanceId)) {
            return Instance::STATUS_MISSING;
        }

        if(static::sailIsUp($instanceId)) {
            return Instance::STATUS_READY;
        }

        return INSTANCE::STATUS_DOWN;
    }

    public static function sailIsUp(string $instanceId): bool
    {
        $output = Executor::cd(
            WorkingDirectory::fromInstanceId($instanceId)
        )->execute('docker-compose ps -q');

        return (bool) $output;
    }
}

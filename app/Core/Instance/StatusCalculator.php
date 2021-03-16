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
        // TODO Make parallel to calculate?
        try {
            Executor::cd(
                WorkingDirectory::fromInstanceId($instanceId)
            )->execute('./vendor/bin/sail artisan help');
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }
}

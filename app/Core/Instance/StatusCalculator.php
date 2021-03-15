<?php

namespace App\Core\Instance;

class StatusCalculator
{

    public static function calculate(string $instanceId)
    {
        if(!app(\App\Core\Contracts\Instance\InstanceRepository::class)->exists($instanceId)) {
            return Instance::STATUS_MISSING;
        }
        // TODO Check if sail is up or not

        return INSTANCE::STATUS_DOWN;
    }
}

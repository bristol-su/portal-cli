<?php

namespace App\Core\Contracts\Instance;

interface InstanceManager
{

    public function exists(string $instanceId): bool;

    public function install(string $instanceId): void;

    public function remove(string $instanceId): void;

}

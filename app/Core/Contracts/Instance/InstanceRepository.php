<?php

namespace App\Core\Contracts\Instance;

interface InstanceRepository
{

    public function exists(string $instanceId): bool;

    public function create(string $instanceId): void;

    public function remove(string $instanceId): void;

}

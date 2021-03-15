<?php

namespace App\Core\Contracts\Instance;

use App\Core\Instance\Instance;

interface InstanceRepository
{

    public function exists(string $instanceId): bool;

    public function create(string $instanceId): Instance;

    public function remove(string $instanceId): void;

}

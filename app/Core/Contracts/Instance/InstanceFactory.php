<?php

namespace App\Core\Contracts\Instance;

use App\Core\Instance\Instance;

interface InstanceFactory
{

    public function createInstanceFromId(string $instanceId): Instance;

}

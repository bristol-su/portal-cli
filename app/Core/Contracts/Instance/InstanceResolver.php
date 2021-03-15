<?php

namespace App\Core\Contracts\Instance;

use App\Core\Instance\Instance;

/**
 * @todo
 */
interface InstanceResolver
{

    public function getInstance(): Instance;

    public function setInstance(Instance $instance);

}

<?php

namespace App\Core\Contracts\Instance;

use App\Core\Site\Site\Instance;

/**
 * @todo
 */
interface InstanceResolver
{

    public function getInstance(): Instance;

    public function setInstance(Instance $instance);

}

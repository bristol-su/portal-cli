<?php

namespace App\Core\Instance;

/**
 * @todo
 */
class InstanceResolver implements \App\Core\Contracts\Instance\InstanceResolver
{

    /**
     * @var Instance
     */
    private Instance $instance;

    public function getInstance(): Instance
    {
        // TODO Probs save in database?
        if($this->instance) {
            return $this->instance;
        }
        throw new \Exception('The instance to use could not be found');
    }

    public function setInstance(Instance $instance)
    {
        $this->instance = $instance;
    }

}

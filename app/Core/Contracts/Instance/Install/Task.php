<?php

namespace App\Core\Contracts\Instance\Install;

use App\Core\Helpers\IO\Proxy;

abstract class Task
{

    /**
     * @var Proxy
     */
    protected Proxy $io;

    /**
     * @var string
     */
    protected string $instanceId;

    public function __construct(Proxy $io, string $instanceId)
    {
        $this->io = $io;
        $this->instanceId = $instanceId;
    }

    abstract public function execute();

}

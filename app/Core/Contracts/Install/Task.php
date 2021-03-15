<?php

namespace App\Core\Contracts\Install;

use App\Core\Helpers\IO\Proxy;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;

abstract class Task
{

    /**
     * @var Proxy
     */
    protected Proxy $io;

    /**
     * @var WorkingDirectory
     */
    protected WorkingDirectory $workingDirectory;

    public function __construct(Proxy $io, WorkingDirectory $workingDirectory)
    {
        $this->io = $io;
        $this->workingDirectory = $workingDirectory;
    }

    abstract public function up(): void;

    abstract public function down(): void;

}

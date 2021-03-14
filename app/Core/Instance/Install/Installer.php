<?php

namespace App\Core\Instance\Install;

use App\Core\IO\IO;

abstract class Installer implements \App\Core\Contracts\Instance\Install\Installer
{

    public function __construct()
    {
    }

    public function install(string $instanceId)
    {
        IO::progressStart(count($this->getTasks()));
        foreach ($this->getTasks() as $task) {
            $class = app($task, ['instanceId' => $instanceId]);
            $class->execute();
            IO::progressStep(1);
        }
        IO::progressFinish();
    }

    abstract protected function getTasks(): array;
}

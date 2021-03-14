<?php

namespace App\Core\Instance\Install\Tasks\CMS;

use App\Core\Helpers\Composer\Composer;
use App\Core\Contracts\Instance\Install\Task;
use App\Core\Helpers\IO\IO;
use Illuminate\Support\Facades\Storage;

class InstallComposerDependencies extends Task
{

    public function execute()
    {
        IO::info('Installing dependencies');

        $composer = new Composer($this->instanceId);
        $composer->install();
    }
}

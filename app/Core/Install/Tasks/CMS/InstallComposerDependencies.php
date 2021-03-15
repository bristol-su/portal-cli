<?php

namespace App\Core\Install\Tasks\CMS;

use App\Core\Helpers\Composer\Composer;
use App\Core\Contracts\Install\Task;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\Storage\Filesystem;

class InstallComposerDependencies extends Task
{

    public function up(): void
    {
        $composer = new Composer($this->workingDirectory);
        $composer->install();
    }

    public function down(): void
    {
        Filesystem::create()->remove(
            $this->workingDirectory->path() . '/vendor'
        );
    }

}

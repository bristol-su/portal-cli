<?php

namespace App\Core\Install\Tasks\CMS;

use App\Core\Contracts\Install\Task;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\Storage\Filesystem;
use App\Core\Helpers\Terminal\Executor;
use Cz\Git\GitRepository;

class InstallYarnDependencies extends Task
{

    public function up(): void
    {
        Executor::cd($this->workingDirectory)->execute(
            './vendor/bin/sail yarn --cwd /var/www/html/vendor/elbowspaceuk/core-module install --non-interactive --no-progress',
        );
        Executor::cd($this->workingDirectory)->execute(
            './vendor/bin/sail yarn --cwd /var/www/html/vendor/elbowspaceuk/core-module run dev --non-interactive --no-progress',
        );
    }

    public function down(): void
    {
        // No down tasks
    }

}

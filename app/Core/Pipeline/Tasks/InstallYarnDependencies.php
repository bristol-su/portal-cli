<?php

namespace App\Core\Pipeline\Tasks;

use App\Core\Contracts\Pipeline\Task;
use App\Core\Helpers\Terminal\Executor;

class InstallYarnDependencies extends Task
{

    public function up(\App\Core\Helpers\WorkingDirectory\WorkingDirectory $workingDirectory): void
    {
        Executor::cd($workingDirectory)->execute(
            './vendor/bin/sail yarn --cwd /var/www/html/vendor/elbowspaceuk/core-module install --non-interactive --no-progress',
        );
        Executor::cd($workingDirectory)->execute(
            './vendor/bin/sail yarn --cwd /var/www/html/vendor/elbowspaceuk/core-module run dev --non-interactive --no-progress',
        );
    }

    public function down(\App\Core\Helpers\WorkingDirectory\WorkingDirectory $workingDirectory): void
    {
        // No down tasks
    }

}

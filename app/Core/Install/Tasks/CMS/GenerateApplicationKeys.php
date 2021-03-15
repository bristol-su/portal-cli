<?php

namespace App\Core\Install\Tasks\CMS;

use App\Core\Contracts\Install\Task;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\Terminal\Executor;

class GenerateApplicationKeys extends Task
{

    public function up(): void
    {
        Executor::cd($this->workingDirectory)->execute('./vendor/bin/sail artisan key:generate');
        Executor::cd($this->workingDirectory)->execute('./vendor/bin/sail artisan key:generate --env=testing');
        Executor::cd($this->workingDirectory)->execute('./vendor/bin/sail artisan key:generate --env=dusk.local');
    }

    public function down(): void
    {
        // No down tasks
    }

}

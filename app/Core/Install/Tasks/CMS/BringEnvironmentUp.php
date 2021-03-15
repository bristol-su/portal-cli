<?php

namespace App\Core\Install\Tasks\CMS;

use App\Core\Contracts\Install\Task;
use App\Core\Helpers\Terminal\Executor;

class BringEnvironmentUp extends Task
{

    public function up(): void
    {
        Executor::cd($this->workingDirectory)
            ->execute('./vendor/bin/sail up -d');
    }

    public function down(): void
    {
        Executor::cd($this->workingDirectory)
            ->execute('./vendor/bin/sail down -v');
    }

}

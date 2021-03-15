<?php

namespace App\Core\Install\Tasks\CMS;

use App\Core\Contracts\Install\Task;
use App\Core\Helpers\Terminal\Executor;

class MigrateDatabase extends Task
{

    public function up(): void
    {
        Executor::cd($this->workingDirectory)->execute('./vendor/bin/sail artisan migrate');
        Executor::cd($this->workingDirectory)->execute('./vendor/bin/sail artisan migrate --env=testing');
    }

    public function down(): void
    {
        // TODO Enable once migrations fixed
//        Executor::cd($this->workingDirectory)->execute('./vendor/bin/sail artisan migrate:rollback');
//        Executor::cd($this->workingDirectory)->execute('./vendor/bin/sail artisan migrate:rollback --env=testing');
    }

}

<?php

namespace App\Core\Install\Tasks\CMS;

use App\Core\Contracts\Install\Task;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\Storage\Filesystem;
use App\Core\Helpers\Terminal\Executor;
use Cz\Git\GitRepository;

class SeedDatabase extends Task
{

    public function up(): void
    {
        Executor::cd($this->workingDirectory)->execute('./vendor/bin/sail artisan module:seed Core --class=CoreDatabaseSeeder');
    }

    public function down(): void
    {
    }

}

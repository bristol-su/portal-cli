<?php

namespace App\Core\Install\Tasks\Frontend;

use App\Core\Contracts\Install\Task;
use App\Core\Helpers\IO\IO;

class NotReadyError extends Task
{

    public function up(): void
    {
        throw new \Exception('The frontend cannot yet be installed through the atlas cli');
    }

    public function down(): void
    {
    }
}

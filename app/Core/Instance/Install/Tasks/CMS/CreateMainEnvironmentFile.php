<?php

namespace App\Core\Instance\Install\Tasks\CMS;

use App\Core\Contracts\Instance\Install\Task;
use Illuminate\Support\Facades\Storage;

class CreateMainEnvironmentFile extends Task
{

    public function execute()
    {
        Storage::copy(
            sprintf('%s/.env.sail.example', $this->instanceId),
            sprintf('%s/.env', $this->instanceId),
        );
    }
}

<?php

namespace App\Core\Install\Tasks\CMS;

use App\Core\Contracts\Install\Task;
use App\Core\Helpers\Storage\Filesystem;

class CreateMainEnvironmentFile extends Task
{

    public function up(): void
    {
        Filesystem::create()->copy(
            $this->getEnvFilePath('.env.sail.example'),
            $this->getEnvFilePath('.env')
        );
    }

    public function down(): void
    {
        Filesystem::create()->remove(
            $this->getEnvFilePath('.env')
        );
    }

    private function getEnvFilePath(string $envFileName) {
        return Filesystem::append($this->workingDirectory->path(), $envFileName);
    }
}

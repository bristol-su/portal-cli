<?php

namespace App\Core\Instance\Install\Tasks\CMS;

use App\Core\Contracts\Instance\Install\Task;
use App\Core\Helpers\IO\IO;
use Cz\Git\GitRepository;
use Illuminate\Support\Facades\Storage;

class CloneGitRepository extends Task
{

    public function execute()
    {
        IO::info('Cloning project');
        GitRepository::cloneRepository(
            config('app.cms-url'),
            Storage::path($this->instanceId),
            [
                '--branch' => 'develop'
            ]
        );
    }
}

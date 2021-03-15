<?php

namespace App\Core\Install\Tasks\CMS;

use App\Core\Contracts\Install\Task;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\Storage\Filesystem;
use Cz\Git\GitRepository;

class CloneGitRepository extends Task
{

    public function up(): void
    {
        GitRepository::cloneRepository(
            config('app.cms-url'),
            $this->workingDirectory->path(),
            [
                '--branch' => 'remove-module-installer'
            ]
        );
    }

    public function down(): void
    {
        Filesystem::create()
            ->remove($this->workingDirectory->path());
    }
}

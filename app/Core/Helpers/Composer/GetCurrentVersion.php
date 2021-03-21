<?php

namespace App\Core\Helpers\Composer;

use App\Core\Helpers\Storage\Filesystem;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;

class GetCurrentVersion
{

    /**
     * @var WorkingDirectory
     */
    private WorkingDirectory $workingDirectory;

    public function __construct(WorkingDirectory $workingDirectory)
    {
        $this->workingDirectory = $workingDirectory;
    }

    public function for(string $package)
    {
        $pattern = sprintf(
            '/"name": "%s",' . PHP_EOL . '"version": "(.*?)",/',
            $package
        );
dd($pattern);
        $composerLock = Filesystem::read(
            Filesystem::append(
                $this->workingDirectory->path(),
                'composer.lock'
            )
        );

        preg_match($pattern, $composerLock, $matches);

        dd($matches);
    }

}

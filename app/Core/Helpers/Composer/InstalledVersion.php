<?php


namespace App\Core\Helpers\Composer;


use App\Core\Helpers\Storage\Filesystem;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use Illuminate\Support\Facades\Facade;

class InstalledVersion
{

    /**
     * @var WorkingDirectory
     */
    private WorkingDirectory $workingDirectory;

    public function __construct(WorkingDirectory $workingDirectory)
    {
        $this->workingDirectory = $workingDirectory;
    }

    private function getSchema(): array
    {
        $composerLock = Filesystem::read(
            Filesystem::append(
                $this->workingDirectory->path(),
                'composer.lock'
            )
        );

        return json_decode($composerLock,true);
    }

    public function isInstalled(string $package): bool
    {
        dd($this->getSchema());
    }

    public function getInstalledVersion(string $package): string
    {
        // TODO: Implement getInstalledVersion() method.
    }

}

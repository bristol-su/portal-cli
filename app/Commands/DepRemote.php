<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Contracts\Site\SiteRepository;
use App\Core\Feature\Feature;
use App\Core\Helpers\Composer\ComposerModifier;
use App\Core\Helpers\Composer\ComposerRunner;
use App\Core\Helpers\Composer\ComposerReader;
use App\Core\Helpers\Composer\Schema\ComposerRepository;
use App\Core\Helpers\Composer\Schema\Schema\ComposerSchema;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\Storage\Filesystem;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use App\Core\Packages\LocalPackage;
use App\Core\Site\Site;
use Cz\Git\GitException;
use Cz\Git\GitRepository;
use Illuminate\Database\Eloquent\Collection;

class DepRemote extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'dep:remote
                            {--F|feature= : The id of the feature}
                            {--P|local-package= : The name of the local package}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Make a module a remote module';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SiteRepository $siteRepository)
    {
        $feature = $this->getFeature('Which feature do you want to use?');
        $site = $feature->getSite();
        /** @var LocalPackage[]|Collection $localPackages */
        $localPackages = LocalPackage::where('feature_id', $feature->getId())->get();

        /** @var LocalPackage $localPackage */
        $localPackage = LocalPackage::where([
            'name' => $this->getOrAskForOption(
                'local-package',
                fn() => $this->choice(
                    'Which dependency would you like to make local?',
                    $localPackages->map(fn($package) => $package->getName())->toArray()
                ),
                fn($value) => $localPackages->filter(fn($package) => $package->getName() === $value)->count() > 0
            ),
            'feature_id' => $feature->getId()
        ])->firstOrFail();

        $workingDirectory = WorkingDirectory::fromSite($site);

        $relativeInstallPath = sprintf('repos/%s', $localPackage->getName());
        $installPath = Filesystem::append(
            $workingDirectory->path(),
            $relativeInstallPath
        );

        IO::info(sprintf('Converting %s into a remote package.', $localPackage->getName()));

        try {
            $this->task('Scanning for changes', fn() => $this->confirmChangesSaved(WorkingDirectory::fromPath($installPath)));
        } catch (\Exception $e) {
            IO::error($e->getMessage());
            return;
        }
        $this->task('Removing remote symlink', fn() => $this->removeSymlinkInComposer($workingDirectory, $relativeInstallPath));
        $this->task('Modify composer.json', fn() => $this->composerRequireRemote($workingDirectory, $localPackage));
        $this->task('Remove the local repository', fn() => $this->removeRepository($installPath));
        $this->task('Clearing stale dependencies', fn() => $this->clearStaleDependencies($workingDirectory, $localPackage->getName()));
        $this->task('Updating composer', fn() => $this->updateComposer($workingDirectory));
        $this->task('Updating project state', fn() => $localPackage->delete());

        IO::success(sprintf('Module %s has been made remote.', $localPackage->getName()));
    }

    private function composerRequireRemote(WorkingDirectory $workingDirectory, LocalPackage $localPackage)
    {
        if($localPackage->getType() === 'direct') {
            ComposerModifier::for($workingDirectory)->changeDependencyVersion($localPackage->getName(), $localPackage->getOriginalVersion());
        } elseif($localPackage->getType() === 'indirect') {
            ComposerModifier::for($workingDirectory)->remove($localPackage->getName());
        }
        return true;
    }

    private function removeSymlinkInComposer(WorkingDirectory $workingDirectory, string $relativeInstallPath)
    {
        ComposerModifier::for($workingDirectory)->removeRepository(
            'path',
            sprintf('./%s', $relativeInstallPath),
            ['symlink' => true]
        );
        return true;
    }

    private function clearStaleDependencies(WorkingDirectory $workingDirectory, string $package)
    {
        $currentVendorPath = Filesystem::append($workingDirectory->path(), 'vendor', $package);
        if(Filesystem::create()->exists($currentVendorPath)) {
            Filesystem::create()->remove($currentVendorPath);
        }
        return true;
    }

    private function updateComposer(WorkingDirectory $workingDirectory)
    {
        ComposerRunner::for($workingDirectory)->update();
        return true;
    }

    private function confirmChangesSaved(WorkingDirectory $workingDirectory)
    {
        if(!$this->confirm(
            sprintf(
                'Please make sure you have checked for any changes. You will lose any unpushed work in [%s] by continuing. Do you wish to continue?',
                $workingDirectory->path()
            )
        )) {
            throw new \Exception('Repository is still installed locally.');
        }
        return true;
    }

    private function removeRepository(string $installPath)
    {
        Filesystem::create()->remove($installPath);
        return true;
    }

}

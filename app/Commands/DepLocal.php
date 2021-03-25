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

class DepLocal extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'dep:local
                            {--F|feature= : The id of the feature}
                            {--P|package= : The composer package name}
                            {--B|branch= : A name for the branch to use}
                            {--R|repository-url= : The URL of the repository}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Make a module a local module';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SiteRepository $siteRepository)
    {
        $feature = $this->getFeature('Which feature should this be done against?');
        $site = $feature->getSite();

        $workingDirectory = WorkingDirectory::fromSite($site);

        $package = $this->getOrAskForOption(
            'package',
            fn() => $this->ask('What package would you like to develop on locally?'),
            fn($value) => $value && is_string($value) && strlen($value) > 3 && LocalPackage::where(['name' => $value, 'feature_id' => $feature->getId()])->count() === 0
        );

        $repositoryUrl = $this->getOrAskForOption(
            'repository-url',
            fn() => $this->ask('What is the git URL of the package repository?', sprintf('git@github.com:%s', $package)),
            fn($value) => $value && is_string($value) && strlen($value) > 3
        );

        $branchName = $this->getOrAskForOption(
            'branch',
            fn() => $this->ask('What should we name the branch?', $feature->getBranch()),
            fn($value) => $value && strlen($value) > 0
        );

        $relativeInstallPath = sprintf('repos/%s', $package);
        $installPath = Filesystem::append(
            $workingDirectory->path(),
            $relativeInstallPath
        );

        IO::info(sprintf('Converting %s into a local package.', $package));

        $this->task('Storing project state', fn() => LocalPackage::create([
            'name' => $package,
            'url' => $repositoryUrl,
            'type' => $this->getDependencyType($workingDirectory, $package),
            'original_version' => $this->getCurrentVersionConstraint($workingDirectory, $package),
            'feature_id' => $feature->getId(),
            'branch' => $branchName
        ]));
        $this->task('Clone the repository', fn() => $this->cloneRepository($installPath, $repositoryUrl));
        $this->task(sprintf('Checkout branch %s', $branchName), fn() => $this->checkoutBranch($branchName, $installPath));
        $this->task('Modify composer.json', fn() => $this->composerRequireLocal($workingDirectory, $package, $branchName));
        $this->task('Adding local symlink', fn() => $this->addSymlinkInComposer($workingDirectory, $relativeInstallPath));
        $this->task('Clearing stale dependencies', fn() => $this->clearStaleDependencies($workingDirectory, $package));
        $this->task('Updating composer', fn() => $this->updateComposer($workingDirectory));

        IO::success(sprintf('Module %s can be found in %s', $package, $relativeInstallPath));
    }

    private function cloneRepository(string $installPath, string $repositoryUrl)
    {
        if(!Filesystem::create()->exists($installPath)) {
            GitRepository::cloneRepository($repositoryUrl, $installPath);
        }
        return true;
    }

    private function checkoutBranch(string $branchName, string $installPath)
    {
        $git = new GitRepository($installPath);
        try {
            $git->checkout($branchName);
        } catch (GitException $e) {
            $git->createBranch($branchName, true);
        }
        return true;
    }

    private function composerRequireLocal(WorkingDirectory $workingDirectory, string $package, string $branchName)
    {
        $reader = ComposerReader::for($workingDirectory);
        try {
            $currentlyInstalled = ComposerReader::for($workingDirectory)->getInstalledVersion($package);
        } catch (\Exception $e) {
            return true;
        }
        $newVersion = sprintf('dev-%s as %s', $branchName, $currentlyInstalled);

        if($reader->isDependency($package, true)) {
            ComposerModifier::for($workingDirectory)->changeDependencyVersion($package, $newVersion);
        } elseif($reader->isInstalled($package)) {
            ComposerModifier::for($workingDirectory)->requireDev($package, $newVersion);
        }
        return true;
    }

    private function addSymlinkInComposer(WorkingDirectory $workingDirectory, string $relativeInstallPath)
    {
        ComposerModifier::for($workingDirectory)->addRepository(
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

    private function getDependencyType(WorkingDirectory $workingDirectory, string $package): string
    {
        $reader = ComposerReader::for($workingDirectory);
        if($reader->isDependency($package, true)) {
            return 'direct';
        } elseif($reader->isInstalled($package)) {
            return 'indirect';
        }
        return 'none';
    }

    private function getCurrentVersionConstraint(WorkingDirectory $workingDirectory, string $package, string $filename = 'composer.json'): ?string
    {
        /** @var ComposerSchema $composer */
        $composer = app(ComposerRepository::class)->get($workingDirectory, $filename);
        foreach($composer->getRequire() as $packageSchema) {
            if($packageSchema->getName() === $package) {
                return $packageSchema->getVersion();
            }
        }
        foreach($composer->getRequireDev() as $packageSchema) {
            if($packageSchema->getName() === $package) {
                return $packageSchema->getVersion();
            }
        }
        return null;
    }

}

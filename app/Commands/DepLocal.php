<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Contracts\Instance\MetaInstanceRepository;
use App\Core\Helpers\Composer\ComposerModifier;
use App\Core\Helpers\Composer\ComposerRunner;
use App\Core\Helpers\Composer\ComposerReader;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\Storage\Filesystem;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
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
                            {--I|instance= : The id of the feature}
                            {--P|package= : The composer package name}
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
    public function handle(MetaInstanceRepository $metaInstanceRepository)
    {

        $instanceId = $this->getInstanceId($metaInstanceRepository);
        $branchName = $this->getBranchName($metaInstanceRepository, $instanceId);
        // TODO Get the working directory of the currently used site/feature
        $workingDirectory = WorkingDirectory::fromInstanceId($instanceId);

        $package = $this->getOrAskForOption(
            'package',
            fn() => $this->ask('What package would you like to develop on locally?'),
            fn($value) => $value && is_string($value) && strlen($value) > 3
        );

        $repositoryUrl = $this->getOrAskForOption(
            'repository-url',
            fn() => $this->ask('What is the git URL of the package repository?', sprintf('git@github.com:%s', $package)),
            fn($value) => $value && is_string($value) && strlen($value) > 3
        );

        $relativeInstallPath = sprintf('repos/%s', $package);
        $installPath = Filesystem::append(
            $workingDirectory->path(),
            $relativeInstallPath
        );

        IO::info(sprintf('Converting %s into a local package.', $package));
        
        $this->task('Clone the repository', fn() => $this->cloneRepository($installPath, $repositoryUrl));
        $this->task(sprintf('Checkout branch %s', $branchName), fn() => $this->checkoutBranch($branchName, $installPath));
        $this->task('Modify composer.json', fn() => $this->composerRequireLocal($workingDirectory, $package, $branchName));
        $this->task('Adding local symlink', fn() => $this->addSymlinkInComposer($workingDirectory, $relativeInstallPath));
        $this->task('Clearing stale dependencies', fn() => $this->clearStaleDependencies($workingDirectory, $package));
        $this->task('Updating composer', fn() => $this->updateComposer($workingDirectory));

        IO::success(sprintf('Module %s can be found in %s', $package, $relativeInstallPath));
    }

    private function getInstanceId(MetaInstanceRepository $metaInstanceRepository): string
    {
        if($metaInstanceRepository->count() === 0) {
            throw new \Exception('No instances are available');
        }

        return $this->getOrAskForOption(
            'instance',
            fn() => $this->choice(
                'Which instance would you like to use?',
                $metaInstanceRepository->all()->map(fn($metaInstance) => $metaInstance->getInstanceId())->toArray()
            ),
            fn($newInstanceId) => $metaInstanceRepository->exists($newInstanceId)
        );
    }

    private function getBranchName(MetaInstanceRepository $metaInstanceRepository, string $instanceId): string
    {
        $branchPrefix = 'feature';
        $type = $metaInstanceRepository->getById($instanceId)->getType();
        if($type === 'fixed') {
            $branchPrefix = 'bug';
        }
        return sprintf('%s/%s', $branchPrefix, $instanceId);
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
        $currentlyInstalled = ComposerReader::for($workingDirectory)->getInstalledVersion($package);
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

}

<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Helpers\Composer\ComposerModifier;
use App\Core\Helpers\Composer\ComposerRunner;
use App\Core\Helpers\Composer\ComposerLockInstalledVersion;
use App\Core\Helpers\Composer\ComposerReader;
use App\Core\Helpers\Composer\Schema\ComposerFilesystem;
use App\Core\Helpers\Composer\Schema\ComposerRepository;
use App\Core\Helpers\Composer\Schema\ComposerSchemaFactory;
use App\Core\Helpers\Composer\Operations\Operations\AddRepository;
use App\Core\Helpers\Composer\Schema\Schema\PackageSchema;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\Storage\Filesystem;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use App\Core\Instance\MetaInstanceRepository;
use Cz\Git\GitException;
use Cz\Git\GitRepository;
use Illuminate\Support\Arr;

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
        if($metaInstanceRepository->count() === 0) {
            IO::error('No instances are installed.');
            return;
        }

        $instanceId = $this->getOrAskForOption(
            'instance',
            fn() => $this->choice(
                'Which instance would you like to use?',
                $metaInstanceRepository->all()->map(fn($metaInstance) => $metaInstance->getInstanceId())->toArray()
            ),
            fn($newInstanceId) => $metaInstanceRepository->exists($newInstanceId)
        );

        $branchPrefix = 'feature';
        $type = $metaInstanceRepository->getById($instanceId)->getType();
        if($type === 'fixed') {
            $branchPrefix = 'bug';
        }
        $branchName = sprintf('%s/%s', $branchPrefix, $instanceId);


        // TODO Get the working directory of the currently installed instance, or a dependency
        $workingDirectory = WorkingDirectory::fromInstanceId($instanceId);

        $package = $this->getOrAskForOption(
            'package',
            fn() => $this->ask('What package would you like to develop on locally?'),
            fn($value) => $value && is_string($value) && strlen($value) > 3
        );

        $repositoryUrl = $this->getOrAskForOption(
            'repository-url',
            fn() => $this->ask('What is the URL of the package?'),
            fn($value) => $value && is_string($value) && strlen($value) > 3
        );

        $relativeInstallPath = sprintf('repos/%s', $package);
        $installPath = Filesystem::append(
            $workingDirectory->path(),
            $relativeInstallPath
        );


        if(Filesystem::create()->exists($installPath)) {
            $this->info('Module already cloned.');
        } else {
            $this->info('Cloning');
            GitRepository::cloneRepository($repositoryUrl, $installPath);
        }

        $this->info(sprintf('Checkout branch %s', $branchName));
        $git = new GitRepository($installPath);
        try {
            $git->checkout($branchName);
        } catch (GitException $e) {
            $git->createBranch($branchName, true);
        }


        $this->info('Changing composer schema');
        $reader = ComposerReader::for($workingDirectory);
        if($reader->isDependency($package, true)) {
            $this->info('In root composer');
            $currentlyInstalled = ComposerReader::for($workingDirectory)->getInstalledVersion($package);
            $newVersion = sprintf('dev-%s as %s', $branchName, $currentlyInstalled);
            $this->info(sprintf('Changing %s to version %s', $package, $newVersion));
            ComposerModifier::for($workingDirectory)
                ->changeDependencyVersion(
                    $package,
                    $newVersion
                );
            // Change version to `dev-branch as currently-installed-version`
        } elseif($reader->isInstalled($package)) {
            $this->info('Installed but not directly required');
            $currentlyInstalled = ComposerReader::for($workingDirectory)->getInstalledVersion($package);
            $newVersion = sprintf('dev-%s as %s', $branchName, $currentlyInstalled);
            ComposerModifier::for($workingDirectory)->requireDev($package, $newVersion);
            $this->info(sprintf('Requiring %s@%s as a dev dependency', $package, $newVersion));
            // Add to composer.json as a dev dependency
            // Change version to `dev-branch as currently-installed-version`
        }


        $this->info('Adding local repository');
        ComposerModifier::for($workingDirectory)
            ->addRepository(
                'path',
                sprintf('./%s', $relativeInstallPath)
            );

//         Delete vendor
//        Filesystem::create()->remove(
//            Filesystem::append($workingDirectory->path(), 'vendor')
//        );

//         Composer update
//        (new ComposerUpdater($workingDirectory))->update();
    }

}

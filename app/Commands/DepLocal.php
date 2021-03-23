<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Helpers\Composer\Composer;
use App\Core\Helpers\Composer\ComposerUpdater;
use App\Core\Helpers\Composer\ComposerLockInstalledVersion;
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

        $instanceId = $this->choice(
            'Which instance would you like to use?',
            $metaInstanceRepository->all()->map(fn($metaInstance) => $metaInstance->getInstanceId())->toArray()
        );

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

        $relativeInstallPath = sprintf('repos/%s', Arr::last(explode('/', $package), null, $package));
        $installPath = Filesystem::append(
            $workingDirectory->path(),
            $relativeInstallPath
        );

        $this->info('Cloning');
        GitRepository::cloneRepository($repositoryUrl, $installPath);

        $this->info('checkout');
        $git = new GitRepository($installPath);
        try {
            $git->checkout($instanceId);
        } catch (GitException $e) {
            $git->createBranch($instanceId, true);
        }

//        Composer::for($workingDirectory)->require($package, '^v1.0.4');

        dd((new ComposerLockInstalledVersion($workingDirectory))->for('elbowspaceuk/blog-module'));

        // If in lock - search for
            // Add to composer.json as a dev dependency
            // Change version to `dev-branch as currently-installed-version`
        // If in root composer
            // Change version to `dev-branch as currently-installed-version`

        Composer::for($workingDirectory)->addRepository('path', sprintf('./%s', $relativeInstallPath));

//         Delete vendor
//        Filesystem::create()->remove(
//            Filesystem::append($workingDirectory->path(), 'vendor')
//        );

//         Composer update
//        (new ComposerUpdater($workingDirectory))->update();
    }

}

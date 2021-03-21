<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Helpers\Composer\ComposerFilesystem;
use App\Core\Helpers\Composer\ComposerRepository;
use App\Core\Helpers\Composer\ComposerSchemaFactory;
use App\Core\Helpers\Composer\Operations\AddRepository;
use App\Core\Helpers\Composer\Schema\Schema\PackageSchema;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use App\Core\Instance\MetaInstanceRepository;

class DepLocal extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'dep:local';

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
    public function handle(MetaInstanceRepository $metaInstanceRepository, ComposerRepository $composerRepository)
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

        $composerSchema = $composerRepository->get($workingDirectory);

        $operator = new AddRepository('path', './Modules/Core');
        $composerSchema = $operator->perform($composerSchema);

        $composerRepository->save($workingDirectory, $composerSchema, 'test/composer.json');

    }

}

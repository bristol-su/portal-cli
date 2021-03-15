<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Contracts\Instance\InstanceRepository;
use App\Core\Contracts\Instance\MetaInstanceRepository;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use App\Core\Install\InstallManager;

class FeatureDelete extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'feature:delete
                            {instance? : The id of the feature}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Delete the given instance';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(InstallManager $installManager, MetaInstanceRepository $metaInstanceRepository, InstanceRepository $instanceRepository)
    {
        if(($instanceId = $this->argument('instance')) === null) {
            if($metaInstanceRepository->count() === 0) {
                IO::error('No instances are installed.');
                return;
            }
            $instanceId = $this->choice(
                'Which instance would you like to delete?',
                $metaInstanceRepository->all()->map(fn($metaInstance) => $metaInstance->getInstanceId())->toArray()
            );
        }

        if(!$instanceRepository->exists($instanceId)) {
            IO::warning('The instance was not found on the filesystem');
        } else {
            $installManager->driver('cms')->uninstall(
                WorkingDirectory::fromInstanceId($instanceId)
            );
            IO::success('Removed the project from your filesystem');
        }

        if($metaInstanceRepository->exists($instanceId)) {
            $metaInstanceRepository->delete($instanceId);
            IO::success('Pruned remaining feature data.');
        }
    }

}

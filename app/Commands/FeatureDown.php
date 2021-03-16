<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Contracts\Instance\InstanceFactory;
use App\Core\Contracts\Instance\InstanceRepository;
use App\Core\Contracts\Instance\MetaInstanceRepository;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\Terminal\Executor;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use App\Core\Instance\Instance;
use App\Core\Pipeline\PipelineManager;

class FeatureDown extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'feature:down
                            {instance? : The id of the feature}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Turn off the given instance';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PipelineManager $installManager, MetaInstanceRepository $metaInstanceRepository, InstanceRepository $instanceRepository)
    {
        if(($instanceId = $this->argument('instance')) === null) {
            if($metaInstanceRepository->count() === 0) {
                IO::error('No instances are installed.');
                return;
            }
            $instanceId = $this->choice(
                'Which instance would you like to turn off?',
                $metaInstanceRepository->all()
                    ->filter(fn($metaInstance) => app(InstanceFactory::class)->createInstanceFromId($metaInstance->getInstanceId())->getStatus() === Instance::STATUS_READY)
                    ->map(fn($metaInstance) => $metaInstance->getInstanceId())->toArray()
            );
        }

        $workingDirectory = WorkingDirectory::fromInstanceId($instanceId);

        IO::info('Turning off site.');

        Executor::cd($workingDirectory)->execute('./vendor/bin/sail down');

        IO::success('Turned off site.');

    }

}

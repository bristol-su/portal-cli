<?php

namespace App\Commands;

use App\Core\Contracts\Instance\InstanceFactory;
use App\Core\Contracts\Instance\MetaInstanceRepository;
use App\Core\Instance\Instance;
use App\Core\Instance\MetaInstance;
use Illuminate\Console\Scheduling\Schedule;
use App\Core\Contracts\Command;

class FeatureList extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'feature:list';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'List all instances of Atlas currently installed.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(MetaInstanceRepository $metaInstanceRepository, InstanceFactory $instanceFactory)
    {
        $metaInstances = $metaInstanceRepository->all();
        $instances = $metaInstances->map(
            fn(MetaInstance $metaInstance) => $instanceFactory->createInstanceFromId($metaInstance->getInstanceId())
        );

        // TODO Add in the URL
        $this->table(
            ['ID', 'Name', 'Description', 'Type', 'Status', 'URL'],
            $instances->map(function(Instance $instance) {
                return [
                    $instance->getInstanceId(),
                    $instance->getMetaInstance()->getName(),
                    $instance->getMetaInstance()->getDescription(),
                    $instance->getMetaInstance()->getType(),
                    $instance->getStatus()
                ];
            })
        );
    }
}

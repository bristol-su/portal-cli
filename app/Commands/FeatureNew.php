<?php

namespace App\Commands;

use App\Core\Contracts\Instance\InstanceManager;
use App\Core\Contracts\Instance\MetaInstanceRepository;
use Illuminate\Support\Str;
use App\Core\Contracts\Command;

class FeatureNew extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'feature:new
                            {name : The name of the feature}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new instance of Atlas.';

    /**
     * @var InstanceManager
     */
    protected $instanceManager;

    /**
     * @var MetaInstanceRepository
     */
    protected $metaInstanceRepository;

    protected $instanceId = null;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(InstanceManager $instanceManager, MetaInstanceRepository $metaInstanceRepository)
    {
        $this->instanceManager = $instanceManager;
        $this->metaInstanceRepository = $metaInstanceRepository;

        $this->info('Installing new instance');

        $instanceManager->create($this->getInstanceId());

        $metaInstance = $metaInstanceRepository->create(
            $this->getInstanceId(),
            $this->getInstanceName()
        );

        $this->getOutput()->success(sprintf('Installed a new Atlas instance.'));
    }

    private function getInstanceId(): string
    {
        if($this->instanceId === null) {
            $id = Str::slug($this->getInstanceName());
            $prefix = '';
            while($this->metaInstanceRepository->exists($id . $prefix) === true) {
                if($prefix === '') {
                    $prefix = 1;
                } else {
                    $prefix++;
                }
            }
            $this->instanceId = $id . $prefix;
        }
        return $this->instanceId;
    }

    private function getInstanceName(): string
    {
        return $this->argument('name');
    }

}

<?php

namespace App\Core\Instance;

use App\Core\Contracts\Instance\InstanceFactory as InstanceFactoryContract;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;

class InstanceFactory implements InstanceFactoryContract
{

    private \App\Core\Contracts\Instance\MetaInstanceRepository $metaInstanceRepository;

    public function __construct(\App\Core\Contracts\Instance\MetaInstanceRepository $metaInstanceRepository)
    {
        $this->metaInstanceRepository = $metaInstanceRepository;
    }

    public function createInstanceFromId(string $instanceId): Instance
    {
        $instance = new Instance();

        $instance->setInstanceId($instanceId);

        $instance->setMetaInstance(
            $this->metaInstanceRepository->getById($instanceId)
        );
        $instance->setWorkingDirectory(
            WorkingDirectory::fromInstanceId($instanceId)
        );

        $instance->setStatus(
            StatusCalculator::calculate($instanceId)
        );

        return $instance;
    }

}

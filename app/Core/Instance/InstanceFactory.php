<?php

namespace App\Core\Instance;

use App\Core\Contracts\Instance\InstanceFactory as InstanceFactoryContract;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;

class InstanceFactory implements InstanceFactoryContract
{

    private \App\Core\Contracts\Site\SiteRepository $siteRepository;

    public function __construct(\App\Core\Contracts\Site\SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    public function createInstanceFromId(string $instanceId): Instance
    {
        $instance = new Instance();

        $instance->setInstanceId($instanceId);

        $instance->setWorkingDirectory(
            WorkingDirectory::fromInstanceId($instanceId)
        );

        $instance->setStatus(
            StatusCalculator::calculate($instanceId)
        );

        $instance->setUrl(
            UrlCalculator::calculate($instanceId)
        );

        return $instance;
    }

}

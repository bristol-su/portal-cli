<?php

namespace App\Core\Instance;

use App\Core\Contracts\Install\Installer;
use App\Core\Helpers\Storage\Filesystem;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;

class InstanceRepository implements \App\Core\Contracts\Instance\InstanceRepository
{

    private \App\Core\Contracts\Instance\InstanceFactory $instanceFactory;

    public function __construct(\App\Core\Contracts\Instance\InstanceFactory $instanceFactory)
    {
        $this->instanceFactory = $instanceFactory;
    }

    public function exists(string $instanceId): bool
    {
        return Filesystem::create()->exists(
            WorkingDirectory::fromInstanceId($instanceId)->path()
        );
    }

    public function create(string $instanceId): Instance
    {
        app(Installer::class)->install($instanceId);

        return $this->instanceFactory->createInstanceFromId($instanceId);
    }

    public function remove(string $instanceId): void
    {
        Filesystem::create()->remove(
            WorkingDirectory::fromInstanceId($instanceId)->path()
        );
    }

}

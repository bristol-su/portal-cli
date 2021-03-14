<?php

namespace App\Core\Instance;

use App\Core\Contracts\Instance\Install\Installer;
use Cz\Git\GitRepository;
use Illuminate\Support\Facades\Storage;

class InstanceManager implements \App\Core\Contracts\Instance\InstanceManager
{

    public function exists(string $instanceId): bool
    {
        return Storage::exists($instanceId);
    }

    public function create(string $instanceId): void
    {
        app(Installer::class)->install($instanceId);
    }

    public function remove(string $instanceId): void
    {
        Storage::deleteDirectory($instanceId);
    }
}

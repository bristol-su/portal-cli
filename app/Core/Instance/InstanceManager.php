<?php

namespace App\Core\Instance;

use App\Core\Contracts\Instance\Installer;
use Cz\Git\GitRepository;
use Illuminate\Support\Facades\Storage;

class InstanceManager implements \App\Core\Contracts\Instance\InstanceManager
{

    public function exists(string $instanceId): bool
    {
        return Storage::exists($instanceId);
    }

    public function install(string $instanceId): void
    {
        $path = Storage::path($instanceId);

        app(Installer::class)->install($path);
    }

    public function remove(string $instanceId): void
    {
        Storage::deleteDirectory($instanceId);
    }
}

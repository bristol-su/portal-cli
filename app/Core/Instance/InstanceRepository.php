<?php

namespace App\Core\Instance;

use App\Core\Contracts\Instance\Install\Installer;
use Illuminate\Support\Facades\Storage;

class InstanceRepository implements \App\Core\Contracts\Instance\InstanceRepository
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

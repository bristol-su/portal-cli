<?php

namespace App\Core\Instance;

use Illuminate\Database\Eloquent\Collection;

class MetaInstanceRepository implements \App\Core\Contracts\Instance\MetaInstanceRepository
{

    public function all(): Collection
    {
       return MetaInstance::all();
    }

    public function create(string $id, string $name): MetaInstance
    {
        $metaInstance = new MetaInstance();

        $metaInstance->instance_id = $id;
        $metaInstance->name = $name;

        $metaInstance->save();

        return $metaInstance;
    }

    public function exists(string $instanceId): bool
    {
        return MetaInstance::where('instance_id', $instanceId)->count() > 0;
    }
}

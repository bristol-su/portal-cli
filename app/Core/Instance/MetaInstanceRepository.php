<?php

namespace App\Core\Instance;

use Illuminate\Database\Eloquent\Collection;

class MetaInstanceRepository implements \App\Core\Contracts\Instance\MetaInstanceRepository
{

    public function all(): Collection
    {
       return MetaInstance::all();
    }

    public function create(string $id, string $name, string $description, string $type, string $installer): MetaInstance
    {
        $metaInstance = new MetaInstance();

        $metaInstance->instance_id = $id;
        $metaInstance->name = $name;
        $metaInstance->description = $description;
        $metaInstance->type = $type;
        $metaInstance->installer = $installer;

        $metaInstance->save();

        return $metaInstance;
    }

    public function exists(string $instanceId): bool
    {
        return MetaInstance::where('instance_id', $instanceId)->count() > 0;
    }

    public function count(): int
    {
        return MetaInstance::count();
    }

    public function getById(string $instanceId): MetaInstance
    {
        return MetaInstance::where('instance_id', $instanceId)->firstOrFail();
    }

    public function delete(string $instanceId) {
        MetaInstance::where('instance_id', $instanceId)->delete();
    }

    public function missing()
    {
        return MetaInstance::all()->filter(fn($metaInstance) => $metaInstance->status === 'missing');
    }
}

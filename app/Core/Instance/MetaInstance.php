<?php

namespace App\Core\Instance;

use Illuminate\Database\Eloquent\Model;

class MetaInstance extends Model
{

    protected $table = 'instances';

    protected $appends = ['status'];

    public function getInstanceId(): string
    {
        return $this->instance_id;
    }

    public function getInstanceName(): string
    {
        return $this->name;
    }

    public function getStatusAttribute(): string
    {
        if(!app(\App\Core\Contracts\Instance\InstanceManager::class)->exists($this->instance_id)) {
            return 'missing';
        }
        return 'found';
    }

}

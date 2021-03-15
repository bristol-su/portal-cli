<?php

namespace App\Core\Instance;

use Illuminate\Database\Eloquent\Model;

class MetaInstance extends Model
{

    protected $table = 'instances';

    public function getInstanceId(): string
    {
        return $this->instance_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getType(): string
    {
        return $this->type;
    }

}

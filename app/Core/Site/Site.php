<?php

namespace App\Core\Site;

use App\Core\Contracts\Instance\InstanceFactory;
use App\Core\Feature\Feature;
use App\Core\Instance\Instance;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{

    protected $table = 'sites';

    public function getId(): int
    {
        return $this->id;
    }

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

    public function getInstaller(): string
    {
        return $this->installer;
    }

    public function instance(): Instance
    {
        return app(InstanceFactory::class)->createInstanceFromId($this->getInstanceId());
    }

    public function features()
    {
        return $this->hasMany(Feature::class);
    }

}

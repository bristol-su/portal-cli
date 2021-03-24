<?php

namespace App\Core\Feature;

use App\Core\Site\Site;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Feature extends Model
{
    use HasFactory;

    protected $table = 'features';

    protected $fillable = [
        'name', 'description', 'type', 'site_id'
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getSite(): Site
    {
        return $this->site;
    }

    public function branchName(): string
    {
        $branchPrefix = 'feature';
        if($this->getType() === 'fixed') {
            $branchPrefix = 'bug';
        }
        return sprintf('%s/%s', $branchPrefix, Str::kebab($this->getName()));
    }
}

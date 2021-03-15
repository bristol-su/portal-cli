<?php

namespace App\Core\Helpers\WorkingDirectory;

use App\Core\Contracts\Helpers\Settings\SettingRepository;
use App\Core\Instance\Instance;
use Illuminate\Support\Str;

class WorkingDirectory
{
    private string $directory;

    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    public function path(): string
    {
        return $this->directory;
    }

    public function set(string $directory)
    {
        $this->directory = $directory;
    }

    public static function fromInstanceId(string $instanceId): WorkingDirectory
    {
        return new WorkingDirectory(
            InstanceDirectoryLocator::fromInstanceId($instanceId)
        );
    }

}

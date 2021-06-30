<?php

namespace Atlas\Sites\AtlasCMSValet;

use OriginEngine\Pipeline\Pipeline;
use OriginEngine\Helpers\Directory\Directory;
use Illuminate\Support\Collection;
use OriginEngine\Pipeline\Tasks\Utils\Closure;
use OriginEngine\Helpers\Terminal\Executor;
use OriginEngine\Pipeline\Tasks\DeleteFiles;


class Down extends Pipeline
{

    public function tasks(): array
    {
        return [
            'unlink-valet' => new Closure(function (Directory $directory, Collection $config) {
                return Executor::cd($directory)->execute('valet unlink');
            }),

            'remove-vendor-files' => new DeleteFiles('vendor')
        ];
    }

    public function aliasedConfig(): array
    {
        return [];
    }
}

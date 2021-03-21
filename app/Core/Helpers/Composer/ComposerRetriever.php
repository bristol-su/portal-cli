<?php

namespace App\Core\Helpers\Composer;

use App\Core\Helpers\Storage\Filesystem;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;

class ComposerRetriever
{

    public function retrieve(WorkingDirectory $directory)
    {
        $path = Filesystem::append(
            $directory->path(),
            'composer.json'
        );

        if(Filesystem::create()->exists($path)) {
            return json_decode(
                Filesystem::read($path), true
            );
        }

        throw new \Exception(
            sprintf('Cannot find composer schema at path [%s].', $path)
        );
    }

}

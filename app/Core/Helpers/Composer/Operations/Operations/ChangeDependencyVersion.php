<?php

namespace App\Core\Helpers\Composer\Operations\Operations;

use App\Core\Contracts\Helpers\Composer\Operation;
use App\Core\Helpers\Composer\Schema\Schema\ComposerSchema;

class ChangeDependencyVersion implements Operation
{

    private string $name;
    private string $version;

    public function __construct(string $name, string $version)
    {
        $this->name = $name;
        $this->version = $version;
    }

    public function perform(ComposerSchema $composerSchema): ComposerSchema
    {
        $require = $composerSchema->getRequire();
        $updatedRequire = [];
        foreach($require as $package) {
            if($package->getName() === $this->name) {
                $package->setVersion($this->version);
            }
            $updatedRequire[] = $package;
        }
        $composerSchema->setRequire($updatedRequire);
        return $composerSchema;
    }

}

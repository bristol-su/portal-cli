<?php

namespace App\Core\Helpers\Composer\Operations;

use App\Core\Contracts\Helpers\Composer\Operation;
use App\Core\Helpers\Composer\Schema\Schema\ComposerSchema;

class ChangeRequireDevVersion implements Operation
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
        $requireDev = $composerSchema->getRequireDev();
        $updatedRequireDev = [];
        foreach($requireDev as $package) {
            if($package->getName() === $this->name) {
                $package->setVersion($this->version);
            }
            $updatedRequireDev[] = $package;
        }
        $composerSchema->setRequireDev($updatedRequireDev);
        return $composerSchema;
    }

}

<?php


namespace App\Core\Helpers\Composer\Operations;


use App\Core\Contracts\Helpers\Composer\Operation;
use App\Core\Helpers\Composer\Schema\Schema\ComposerSchema;
use App\Core\Helpers\Composer\Schema\Schema\PackageSchema;

class RemoveDev implements Operation
{

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function perform(ComposerSchema $composerSchema): ComposerSchema
    {
        $requireDev = $composerSchema->getRequireDev();
        $updatedRequireDev = [];
        $found = false;
        foreach($requireDev as $package) {
            if($package->getName() === $this->name) {
                $found = true;
                continue;
            }
            $updatedRequireDev[] = $package;
        }

        if($found === false) {
            throw new \Exception(
                sprintf('Package %s was not required as a dev dependency', $this->name)
            );
        }

        $composerSchema->setRequireDev($updatedRequireDev);
        return $composerSchema;
    }
}

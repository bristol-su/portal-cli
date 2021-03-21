<?php


namespace App\Core\Helpers\Composer\Operations\Operations;


use App\Core\Contracts\Helpers\Composer\Operation;
use App\Core\Helpers\Composer\Schema\Schema\ComposerSchema;

class Remove implements Operation
{

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function perform(ComposerSchema $composerSchema): ComposerSchema
    {
        $require = $composerSchema->getRequire();
        $updatedRequire = [];
        $found = false;
        foreach($require as $package) {
            if($package->getName() === $this->name) {
                $found = true;
                continue;
            }
            $updatedRequire[] = $package;
        }

        if($found === false) {
            throw new \Exception(
                sprintf('Package %s was not required as a  dependency', $this->name)
            );
        }

        $composerSchema->setRequire($updatedRequire);
        return $composerSchema;
    }
}

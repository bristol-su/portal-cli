<?php

namespace App\Core\Setup\Steps;

use App\Core\Contracts\Settings\SettingRepository;
use App\Core\Contracts\Setup\SetupStep;

class CreateWorkingDirectory extends SetupStep
{

    public function run()
    {
        $directory = $this->getDirectory();

        if(! is_dir($directory) && !mkdir($directory, 0777, true)) {
            throw new \Exception(sprintf('Could not create directory %s.', $directory));
        }
    }

    private function getDirectory(): string
    {
        return $_SERVER['HOME'] . '/.atlas-cli';
    }
}

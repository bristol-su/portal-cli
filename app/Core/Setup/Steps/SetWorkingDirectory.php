<?php

namespace App\Core\Setup\Steps;

use App\Core\Contracts\Setup\SetupStep;
use function PHPUnit\Framework\directoryExists;

class SetWorkingDirectory extends SetupStep
{

    public function run()
    {
        $directory = $this->getDirectory();

        if(! is_dir($directory) && !mkdir($directory, 0777, true)) {
            $this->io->error(sprintf('Could not create directory %s', $directory));
        }

        // Set the settings

        $this->io->info(sprintf('Using working directory %s', realpath($directory)));
    }

    private function getDirectory(): string
    {
        return $this->io->ask('What do you want the working directory to be?');
    }
}

<?php

namespace App\Core\Setup\Steps;

use App\Core\Contracts\Settings\SettingRepository;
use App\Core\Contracts\Setup\SetupStep;
use Illuminate\Support\Str;
use function PHPUnit\Framework\directoryExists;

class SetWorkingDirectory extends SetupStep
{

    public function run()
    {
        $directory = $this->getDirectory();

        if(! is_dir($directory) && !mkdir($directory, 0777, true)) {
            throw new \Exception(sprintf('Could not create directory %s.', $directory));
        }

        if(($realpath = realpath($directory)) === false) {
            throw new \Exception(sprintf('Directory %s could not be loaded.', $realpath));
        }

        app(SettingRepository::class)
            ->set('working-directory', $realpath);

        $this->io->info(sprintf('Using working directory %s', $realpath));
    }

    private function getDirectory(): string
    {
        return $this->io->ask(
            'What do you want the working directory to be?',
            null,
            function($directory) {
                if(Str::contains($directory, '~')) {
                    throw new \RuntimeException('Cannot locate your home directory ~. Please enter a relative or absolute path.');
                }
                if($directory === null) {
                    throw new \RuntimeException('Please enter a directory');
                }

                return $directory;
            }
        );
    }
}

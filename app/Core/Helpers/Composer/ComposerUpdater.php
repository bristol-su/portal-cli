<?php

namespace App\Core\Helpers\Composer;

use App\Core\Helpers\Terminal\Executor;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use App\Core\Instance\Instance;

class ComposerUpdater
{

    private WorkingDirectory $workingDirectory;

    public function __construct(WorkingDirectory $workingDirectory)
    {
        $this->workingDirectory = $workingDirectory;
    }

    public function update()
    {
        $this->composer(
            sprintf(
                'update --working-dir %s --no-cache --quiet --no-interaction --ansi',
                $this->workingDirectory->path()
            )
        );
    }

    public function install()
    {
        $this->composer(
            sprintf(
                'install --working-dir %s --no-cache --quiet --no-interaction --ansi',
                $this->workingDirectory->path()
            )
        );
    }

    public function composer(string $command)
    {
        $docker = new Docker();
        $docker->addVolume($this->workingDirectory->path(), '/opt');

        $docker->addVolume('$SSH_AUTH_SOCK', '/ssh-auth.sock');
        $docker->setEnvironmentVariable('SSH_AUTH_SOCK', '/ssh-auth.sock');

        $docker->setEnvironmentVariable('GITHUB_KEYSCAN', '"$(ssh-keyscan github.com 2> /dev/null)"');

        $docker->setWorkingDirectory('/opt');

        $docker->image('laravelsail/php74-composer:latest');

        $docker->run(
            sprintf('echo $GITHUB_KEYSCAN >> ~/.ssh/known_hosts && composer %s', $command)
        );

        return Executor::cd($this->workingDirectory)
            ->execute($docker);
    }

}

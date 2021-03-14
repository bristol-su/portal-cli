<?php

namespace App\Core\Helpers\Composer;

use App\Core\Helpers\Terminal\Executor;
use Illuminate\Support\Facades\Storage;

class Composer
{

    protected string $instanceId;

    public function __construct(string $instanceId)
    {
        $this->instanceId = $instanceId;
    }

    public function update()
    {
        $path = Storage::path($this->instanceId);

//        $this->exec('
//            docker run --rm \
//                -v ' . $path . ':/opt \
//                -v $SSH_AUTH_SOCK:/ssh-auth.sock \
//                -e SSH_AUTH_SOCK=/ssh-auth.sock \
//                -w /opt \
//                laravelsail/php74-composer:latest \
//                ssh-keyscan github.com >> ~/.ssh/known_hosts && composer update --working-dir ' . $path . ' --no-cache --quiet --no-interaction --ansi
//        ');
    }

    public function install()
    {
        $path = Storage::path($this->instanceId);

        Executor::execute('
            docker run --rm \
                -v ' . $path . ':/opt \
                -v $SSH_AUTH_SOCK:/ssh-auth.sock \
                -e SSH_AUTH_SOCK=/ssh-auth.sock \
                -w /opt \
                laravelsail/php74-composer:latest \
                ssh-keyscan github.com >> ~/.ssh/known_hosts && composer install --working-dir ' . $path . ' --no-cache --quiet --no-interaction --ansi
        ');
    }

    public function composer(string $command, string $directory)
    {
        $docker = new Docker();
        $docker->addVolume($directory, '/opt');
        $composer[] = sprintf('-v %s:/opt', $directory);
        $composer[] = [
            '-v $SSH_AUTH_SOCK:/ssh-auth.sock',
            '-e SSH_AUTH_SOCK=/ssh-auth.sock \
        ]
        return Executor::cd($directory)
            ->execute($command);
    }

}

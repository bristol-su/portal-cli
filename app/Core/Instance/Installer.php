<?php

namespace App\Core\Instance;

use Cz\Git\GitRepository;

class Installer implements \App\Core\Contracts\Instance\Installer
{

    public function install(string $path)
    {
        GitRepository::cloneRepository(
            config('app.cms-url'),
            $path,
            [
                '--branch' => 'develop'
            ]
        );

        // TODO move to COR like setup

        /*
         * - Install the composer dependencies
         * - Copy the environment file
         * - Check over environment file ports to ensure they're all empty
         * - Create and modify testing environment
         * - Create and modify dusk environment
         * - Bring up environment with sail
         * - Install yarn dependencies
         * - Run yarm
         * - Generate keys for 3 envs, migrate the db, seed the core module.
         */
    }

}

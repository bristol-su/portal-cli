<?php

namespace App\Core\Instance\Install;

use Cz\Git\GitRepository;
use Illuminate\Contracts\Config\Repository;

class CMSInstaller extends Installer
{

    /**
     * @var Repository
     */
    private Repository $config;

    public function __construct(Repository $config)
    {
        parent::__construct();
        $this->config = $config;
    }

    protected function getTasks(): array
    {
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
        return $this->config->get('app.install.cms.tasks', []);
    }
}

<?php

namespace App\Core\Install;

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
        $this->config = $config;
    }

    protected function getTasks(): array
    {
        return $this->config->get('app.install.cms.tasks', []);
    }
}

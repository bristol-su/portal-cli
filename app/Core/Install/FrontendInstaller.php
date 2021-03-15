<?php

namespace App\Core\Install;

use Illuminate\Contracts\Config\Repository;

class FrontendInstaller extends Installer
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
        return $this->config->get('app.install.frontend.tasks', []);
    }
}

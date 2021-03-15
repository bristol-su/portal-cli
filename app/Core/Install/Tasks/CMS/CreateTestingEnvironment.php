<?php

namespace App\Core\Install\Tasks\CMS;

use App\Core\Contracts\Install\Task;
use App\Core\Helpers\Env\EnvRepository;
use App\Core\Helpers\IO\Proxy;
use App\Core\Helpers\Storage\Filesystem;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;

class CreateTestingEnvironment extends Task
{

    /**
     * @var EnvRepository
     */
    private EnvRepository $envRepository;

    public function __construct(Proxy $io, WorkingDirectory $workingDirectory)
    {
        parent::__construct($io, $workingDirectory);
        $this->envRepository = new EnvRepository($workingDirectory);
    }

    public function up(): void
    {
        Filesystem::create()->copy(
            $this->getEnvFilePath('.env'),
            $this->getEnvFilePath('.env.testing')
        );

        $env = $this->envRepository->get(EnvRepository::TESTING);
        $env->setVariable('APP_ENV', 'testing');
        $env->setVariable('DB_CONNECTION', 'mysql_testing');
        $this->envRepository->update($env);
    }

    public function down(): void
    {
        Filesystem::create()->remove(
            $this->getEnvFilePath('.env.testing')
        );
    }

    private function getEnvFilePath(string $envFileName) {
        return Filesystem::append($this->workingDirectory->path(), $envFileName);
    }
}

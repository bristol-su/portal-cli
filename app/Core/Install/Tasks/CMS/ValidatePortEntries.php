<?php

namespace App\Core\Install\Tasks\CMS;

use App\Core\Contracts\Install\Task;
use App\Core\Helpers\Env\EnvRepository;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\IO\Proxy;
use App\Core\Helpers\Port\Port;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use Illuminate\Contracts\Config\Repository;

class ValidatePortEntries extends Task
{

    private array $usedPorts = [];

    /**
     * @var Repository
     */
    protected Repository $config;

    /**
     * @var EnvRepository
     */
    protected EnvRepository $envRepository;

    protected \App\Core\Helpers\Env\Env $env;

    public function __construct(Proxy $io, WorkingDirectory $workingDirectory, Repository $config)
    {
        parent::__construct($io, $workingDirectory);
        $this->config = $config;
        $this->envRepository = new EnvRepository($workingDirectory);
        $this->env = $this->envRepository->get(EnvRepository::ROOT);
    }

    public function up(): void
    {
        foreach($this->config->get('app.install.cms.ports') as $portName => $envName) {
            $port = (int) $this->env->getVariable($envName, null);

            while(!$port || $this->isPortTaken($port)) {
                $port = $this->promptPortChange($portName, $port);
            }

            $this->savePort($envName, $port);
        }
        $this->envRepository->update($this->env, EnvRepository::ROOT);
    }

    private function isPortTaken(int $port): bool
    {
        return in_array($port, $this->usedPorts)
            || Port::isTaken($port);
    }

    private function promptPortChange(string $portName, int $port = null): int
    {
        $suggestedPort = $port + 1;
        while($this->isPortTaken($suggestedPort)) {
            $suggestedPort++;
        }
        return (int) $this->io->ask(
            sprintf('Port %s is in use, please choose a port for the %s', $port ?? '[no port]', $portName),
            $suggestedPort,
            fn($port) => $this->validateIsPort($port)
        );
    }

    private function validateIsPort($port): int
    {
        if(!$port || (int) $port <= 0) {
            throw new \Exception(sprintf('%s is not a valid port', $port));
        }
        return (int) $port;
    }

    private function savePort(string $envName, int $port)
    {
        $this->usedPorts[] = $port;
        $this->env->setVariable($envName, $port);
    }

    public function down(): void
    {
        // No down tasks
    }
}

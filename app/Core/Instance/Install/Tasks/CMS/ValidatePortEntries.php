<?php

namespace App\Core\Instance\Install\Tasks\CMS;

use App\Core\Contracts\Instance\Install\Task;
use App\Core\Helpers\Env\EnvRepository;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\IO\Proxy;
use App\Core\Helpers\Port\PortChecker;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Facades\Storage;

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

    public function __construct(Proxy $io, string $instanceId, Repository $config, EnvRepository $envRepository)
    {
        parent::__construct($io, $instanceId);
        $this->config = $config;
        $this->initialiseEnv($instanceId);
        $this->envRepository = $envRepository;
    }

    public function execute()
    {
        foreach($this->config->get('app.install.cms.ports') as $portName => $envName) {
            $port = $this->env->getVariable($envName, null);

            while($port !== null && $this->isPortTaken($port)) {
                $port = $this->promptPortChange($portName, $port);
            }

            $this->savePort($envName, $port);
        }
        IO::info('Ports determined');
    }

    private function isPortTaken(int $port): bool
    {
        return in_array($port, $this->usedPorts)
            || PortChecker::isTaken($port);
    }

    private function promptPortChange(string $portName, int $port): int
    {
        return (int) $this->io->ask(
            sprintf('Port %u is in use, please choose a port for the %s', $port, $portName),
            $port + 1,
            fn($port) => (int) $port > 0
        );
    }

    private function savePort(string $envName, int $port)
    {
        $this->usedPorts[] = $port;
    }

    private function initialiseEnv(string $instanceId)
    {
        $this->env = $this->envRepository->getForInstance($instanceId, EnvRepository::ROOT);
    }
}

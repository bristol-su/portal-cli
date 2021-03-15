<?php

namespace App\Core\Helpers\Env;

use App\Core\Helpers\Storage\Filesystem;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use App\Core\Instance\Instance;
use Illuminate\Support\Str;

class EnvRepository
{

    const ROOT = '.env';

    const TESTING = '.env.testing';

    const DUSK = '.env.dusk.local';

    /**
     * @var WorkingDirectory
     */
    private WorkingDirectory $workingDirectory;

    public function __construct(WorkingDirectory $workingDirectory)
    {
        $this->workingDirectory = $workingDirectory;
    }

    public function get(string $type = null)
    {
        $envRetriever = new EnvRetriever($this->workingDirectory->path());
        $env = $envRetriever->get($type ?? static::ROOT);

        return EnvFactory::fromDotEnv($env);
    }

    public function update(Env $env, $type = null): void
    {
        $path = Filesystem::append($this->workingDirectory->path(), $type ?? static::ROOT);

        $envFile = '';
        foreach($env->getVariables() as $name => $value) {
            $pattern = '%s=%s';
            if(Str::contains($value, ' ')) {
                $pattern = '%s="%s"';
            }
            $envFile .= sprintf($pattern, $name, $value) . PHP_EOL;
        }
        Filesystem::create()->remove($path);
        Filesystem::create()->appendToFile($path, $envFile);
    }

}

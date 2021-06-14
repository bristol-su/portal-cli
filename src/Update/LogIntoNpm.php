<?php

namespace Atlas\Update;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use OriginEngine\Helpers\Directory\Directory;
use OriginEngine\Helpers\Storage\Filesystem;
use OriginEngine\Helpers\Terminal\Executor;
use OriginEngine\Pipeline\Task;
use OriginEngine\Pipeline\TaskResponse;

class LogIntoNpm extends Task
{

    private string $npmrcPath;

    /**
     * LogIntoNpm constructor.
     * @param string $registry The name of the npm registry to log into. Don't include slashes or a protocol. Example: npm.pkg.github.com
     * @param string $authToken The auth token/personal access token for Github to log in using
     */
    public function __construct(string $registry, string $authToken, string $scope = null)
    {
        parent::__construct([
            'registry' => $registry,
            'auth-token' => $authToken,
            'scope' => $scope
        ]);
    }

    protected function execute(Directory $workingDirectory, Collection $config): TaskResponse
    {
        $home = Executor::cd(Directory::fromFullPath('~'))->execute('pwd');
        $npmrcPath = $home . DIRECTORY_SEPARATOR . '.npmrc';

        $npmrc = Filesystem::create()->exists($npmrcPath) ? Filesystem::create()->read($npmrcPath) : '';
        if (!Str::contains($npmrc, $config->get('registry'))) {
            $npmrc .= PHP_EOL . sprintf('//%s/:_authToken=%s', $config->get('registry'), $config->get('auth-token'));
            if($config->get('scope')) {
                $npmrc .= PHP_EOL . sprintf('@%s:registry=https://%s', $config->get('scope'), $config->get('registry'));
            }
            $this->writeDebug('Added new auth to npmrc');
        }

        $this->export('npmrc', $npmrc);
        Filesystem::create()->dumpFile($npmrcPath, $npmrc);
        return $this->succeeded();
    }

    protected function undo(Directory $workingDirectory, bool $status, Collection $config, Collection $output): void
    {
        $home = Executor::cd(Directory::fromFullPath('~'))->execute('pwd');
        $npmrcPath = $home . DIRECTORY_SEPARATOR . '.npmrc';

        $npmrc = Filesystem::create()->exists($npmrcPath) ? Filesystem::create()->read($npmrcPath) : '';
        if (!Str::contains($npmrc, $config->get('registry'))) {
            $npmrc = Str::remove(
                PHP_EOL . sprintf('//%s/:_authToken=%s', $config->get('registry'), $config->get('auth-token')),
                $npmrc,
                true
            );
            if($config->get('scope')) {
                $npmrc = Str::remove(
                    PHP_EOL . sprintf('@%s:registry=https://%s', $config->get('scope'), $config->get('registry')),
                    $npmrc,
                    true
                );
            }
            $this->writeDebug('Removed auth to npmrc');
        }

        Filesystem::create()->dumpFile($npmrcPath, $npmrc);
    }

    protected function upName(Collection $config): string
    {
        return 'Logging into npm';
    }

    protected function downName(Collection $config): string
    {
        return 'Logging out of npm';
    }
}

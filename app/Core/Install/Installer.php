<?php

namespace App\Core\Install;

use App\Core\Helpers\IO\IO;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use Illuminate\Support\Str;
use function Symfony\Component\Translation\t;

abstract class Installer implements \App\Core\Contracts\Install\Installer
{

    public function install(WorkingDirectory $directory)
    {
        $completedTasks = [];
        IO::info(sprintf('Starting installation to %s', $directory->path()));
        try {
            foreach ($this->getTasks() as $task) {
                $class = app($task, ['workingDirectory' => $directory]);
                IO::task(
                    str_replace('-', ' ', $this->taskName($task)),
                    fn() => $class->up(),
                    'installing...'
                );
                $completedTasks[] = $class;
            }
        } catch (\Exception $e) {
            IO::error('Installation failed, aborting.');

            foreach(array_reverse($completedTasks) as $task) {
                IO::task(
                    str_replace('-', ' ', $this->taskName(get_class($task))),
                    fn() => $task->down(),
                    'uninstalling...'
                );
            }

            IO::info('Rollback complete.');

            throw $e;
        }
    }

    public function uninstall(WorkingDirectory $directory)
    {
        try {
            foreach (array_reverse($this->getTasks()) as $task) {
                $class = app($task, ['workingDirectory' => $directory]);
                $class->down();
            }
        } catch (\Exception $e) {
            throw $e;
        }

    }

    abstract protected function getTasks(): array;

    private function taskName(string $task): string
    {
        $reflectionClass = new \ReflectionClass($task);
        return Str::title(
            Str::kebab(
                $reflectionClass->getShortName()
            )
        );
    }
}

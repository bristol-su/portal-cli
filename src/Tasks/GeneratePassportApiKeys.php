<?php

namespace Portal\Tasks;

use Illuminate\Support\Collection;
use OriginEngine\Pipeline\Task;
use OriginEngine\Helpers\Terminal\Executor;
use OriginEngine\Helpers\Directory\Directory;
use OriginEngine\Pipeline\TaskResponse;

class GeneratePassportApiKeys extends Task
{

    protected function execute(Directory $workingDirectory, Collection $config): TaskResponse
    {
        $output = Executor::cd($workingDirectory)->execute(
            './vendor/bin/sail artisan passport:keys --force'
        );

        $this->writeDebug('artisan output: ' . $output);

        return $this->succeeded([
            'output' => $output
        ]);
    }

    protected function undo(Directory $workingDirectory, bool $status, Collection $config, Collection $output): void
    {
        // No undoing
    }

    protected function upName(Collection $config): string
    {
        return 'Generate passport API keys';
    }

    protected function downName(Collection $config): string
    {
        return 'Remove passport API keys';
    }

}

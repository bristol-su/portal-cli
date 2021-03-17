<?php

namespace App\Core\Pipeline\Tasks;

use App\Core\Contracts\Pipeline\Task;
use App\Core\Helpers\Terminal\Executor;
use App\Core\Pipeline\ProvisionedTask;

class InstallYarnDependencies extends Task
{

    public static function provision(string $cwd = ''): \App\Core\Pipeline\ProvisionedTask
    {
        return ProvisionedTask::provision(static::class)
            ->dependencies([
                'cwd' => $cwd
            ]);
    }

    public function up(\App\Core\Helpers\WorkingDirectory\WorkingDirectory $workingDirectory): void
    {
        $command = './vendor/bin/sail yarn';

        if($this->config->get('cwd')) {
            $command .= sprintf(' --cwd %s', $this->config->get('cwd'));
        }

        $command .= ' install --non-interactive --no-progress';

        Executor::cd($workingDirectory)->execute($command);
    }

    public function down(\App\Core\Helpers\WorkingDirectory\WorkingDirectory $workingDirectory): void
    {
        // No down tasks
    }


}

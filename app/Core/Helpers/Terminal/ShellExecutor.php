<?php


namespace App\Core\Helpers\Terminal;


use App\Core\Contracts\Helpers\Terminal\Executor as ExecutorContract;

class ShellExecutor implements ExecutorContract
{

    private string $workingDirectory;

    public function cd(string $directory): ExecutorContract
    {
        $this->workingDirectory = $directory;

        return $this;
    }

    protected function formatCommand(string $command): string
    {
        if($this->workingDirectory) {
            $command = sprintf(
                'cd %s; %s',
                $this->workingDirectory,
                $command
            );
        }
        return $command;
    }

    public function execute(string $command): string
    {
        ob_start();
        $output = shell_exec(
            $this->formatCommand($command)
        );

        ob_end_clean();
        return $output;
    }

}

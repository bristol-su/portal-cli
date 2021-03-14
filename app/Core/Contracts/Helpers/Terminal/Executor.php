<?php

namespace App\Core\Contracts\Helpers\Terminal;

interface Executor
{

    public function execute(string $command): string;

    public function cd(string $directory): Executor;

}

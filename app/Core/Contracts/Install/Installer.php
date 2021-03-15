<?php

namespace App\Core\Contracts\Install;

use App\Core\Helpers\WorkingDirectory\WorkingDirectory;

interface Installer
{

    public function install(WorkingDirectory $directory);

    public function uninstall(WorkingDirectory $directory);

}

<?php

namespace App\Core\Install;

use Illuminate\Support\Manager;

class InstallManager extends Manager
{

    public function getDefaultDriver()
    {
        return null;
    }
}

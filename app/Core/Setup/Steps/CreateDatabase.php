<?php

namespace App\Core\Setup\Steps;

use App\Core\Contracts\Setup\SetupStep;
use Illuminate\Support\Facades\Storage;

class CreateDatabase extends SetupStep
{

    public function run()
    {
        if(!Storage::disk('config')->exists('atlas-cli.sqlite')) {
            $this->io->info('Creating database');
            Storage::disk('config')->put('atlas-cli.sqlite', '');
        }
    }
}

<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Setup\SetupManager;

class Setup extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'setup';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SetupManager $setupManager)
    {
        $setupManager->setup();
    }
}

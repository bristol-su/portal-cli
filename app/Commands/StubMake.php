<?php

namespace App\Commands;

use App\Core\Contracts\Command;

class StubMake extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'stub:make';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Use a stub';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }

}

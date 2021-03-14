<?php

namespace App\Commands;

use App\Core\Contracts\Instance\MetaInstanceRepository;
use Illuminate\Console\Scheduling\Schedule;
use App\Core\Contracts\Command;

class FeatureList extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'feature:list';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'List all instances of Atlas currently installed.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(MetaInstanceRepository $metaInstanceRepository)
    {
        $metaInstances = $metaInstanceRepository->all();

        $this->table(
            ['ID', 'Name', 'Path', 'Status'],
            $metaInstances->map(
                fn($metaInstance) => $metaInstance->only(['instance_id', 'name', 'path', 'status'])
            )
        );
    }
}

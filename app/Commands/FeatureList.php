<?php

namespace App\Commands;

use App\Core\Contracts\Instance\MetaInstanceRepository;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

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

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}

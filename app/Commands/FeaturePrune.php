<?php

namespace App\Commands;

use App\Core\Contracts\Instance\MetaInstanceRepository;
use App\Core\IO\IO;
use App\Core\Contracts\Command;

class FeaturePrune extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'feature:prune';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Remove all features that have a missing local directory';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(MetaInstanceRepository $metaInstanceRepository)
    {
        $metaInstances = $metaInstanceRepository->missing();
        if(count($metaInstances) > 0) {
            foreach($metaInstances as $metaInstance) {
                $metaInstanceRepository->delete($metaInstance->instance_id);
                IO::info(sprintf('Cleared feature %s', $metaInstance->name));
            }
        } else {
            IO::info('No features need pruning.');
        }
    }

}

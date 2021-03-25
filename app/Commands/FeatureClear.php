<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Contracts\Feature\FeatureResolver;
use App\Core\Helpers\IO\IO;

class FeatureClear extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'feature:clear';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Do not use any feature by default.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(FeatureResolver $featureResolver)
    {
        IO::info('Clearing default feature.');

        if($featureResolver->hasFeature()) {
            $featureResolver->clearFeature();
        }
    }

}

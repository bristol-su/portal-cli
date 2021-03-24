<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Contracts\Feature\FeatureResolver;
use App\Core\Helpers\IO\IO;

class FeatureUse extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'feature:use
                            {--S|feature= : The id of the feature}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Use the given feature by default.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(FeatureResolver $featureResolver)
    {
        $feature = $this->getFeature('Which feature would you like to use by default?', null, true);

        IO::info('Switching default feature to ' . $feature->getName() . '.');

        $featureResolver->setFeature($feature);

    }

}
